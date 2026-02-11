import React, { useState, useEffect } from 'react';
import { loadStripe } from '@stripe/stripe-js';
import { Elements, CardElement, useStripe, useElements } from '@stripe/react-stripe-js';

const CheckoutForm = ({ clientSecret, onPaymentSuccess, onBack, amount }) => {
    const stripe = useStripe();
    const elements = useElements();
    const [error, setError] = useState(null);
    const [processing, setProcessing] = useState(false);

    const handleSubmit = async (event) => {
        event.preventDefault();
        setProcessing(true);

        if (!stripe || !elements) return;

        const payload = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: elements.getElement(CardElement),
            }
        });

        if (payload.error) {
            setError(`Pago fallido: ${payload.error.message}`);
            setProcessing(false);
        } else {
            setError(null);
            setProcessing(false);
            onPaymentSuccess(payload.paymentIntent);
        }
    };

    return (
        <form onSubmit={handleSubmit} className="space-y-6 animate-fade-in-up">
            <div className="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-100 dark:border-slate-700">
                <h3 className="text-lg font-bold text-slate-900 dark:text-white mb-4">Información de Pago</h3>
                <div className="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl shadow-inner mb-4">
                    <CardElement options={{
                        style: {
                            base: {
                                fontSize: '16px',
                                color: '#424770',
                                '::placeholder': { color: '#aab7c4' },
                            },
                            invalid: { color: '#9e2146' },
                        },
                    }} />
                </div>
                {error && <div className="text-red-500 text-sm font-bold mb-4">{error}</div>}
                
                <p className="text-xs text-slate-500 mb-6 font-medium">
                    Se realizará un cargo de <span className="text-primary font-bold">5.00€</span> en concepto de fianza para confirmar tu reserva. El resto se abonará en el salón.
                </p>
            </div>

            <div className="flex justify-between gap-4">
                <button 
                    type="button"
                    onClick={onBack}
                    className="px-6 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-bold hover:bg-slate-50 transition-colors"
                >
                    Atrás
                </button>
                <button 
                    disabled={processing || !stripe}
                    className={`flex-1 py-4 rounded-xl font-bold text-white shadow-lg transition-all ${processing ? 'bg-slate-400' : 'bg-primary hover:bg-primary-hover shadow-primary/20'}`}
                >
                    {processing ? 'Procesando...' : `Pagar 5.00€ y Confirmar`}
                </button>
            </div>
        </form>
    );
};

export default function BookingWizard({ stripePublicKey }) {
    const [stripePromise, setStripePromise] = useState(null);
    const [step, setStep] = useState(1);
    const [services, setServices] = useState([]);
    const [employees, setEmployees] = useState([]);
    const [availableSlots, setAvailableSlots] = useState([]);
    
    const [selectedService, setSelectedService] = useState(null);
    const [selectedEmployee, setSelectedEmployee] = useState(null);
    const [selectedDate, setSelectedDate] = useState(new Date().toISOString().split('T')[0]);
    const [selectedTime, setSelectedTime] = useState(null);
    const [clientSecret, setClientSecret] = useState(null);
    
    const [loading, setLoading] = useState(false);
    const [bookingComplete, setBookingComplete] = useState(false);

    useEffect(() => {
        if (stripePublicKey) {
            setStripePromise(loadStripe(stripePublicKey));
        }
    }, [stripePublicKey]);

    useEffect(() => {
        fetch('/api/services')
            .then(res => res.json())
            .then(data => setServices(data));
    }, []);

    const fetchSlots = () => {
        setLoading(true);
        fetch('/api/availability')
            .then(res => res.json())
            .then(data => {
                setAvailableSlots(data.slots);
                setLoading(false);
            });
    };

    const handleServiceSelect = (service) => {
        setSelectedService(service);
        setStep(2);
        setLoading(true);
        fetch(`/api/employees?service=${service.id}`)
            .then(res => res.json())
            .then(data => {
                setEmployees(data);
                setLoading(false);
            });
    };

    const handleEmployeeSelect = (employee) => {
        setSelectedEmployee(employee);
        setStep(3);
        fetchSlots();
    };

    const handleDateTimeSelect = (time) => {
        setSelectedTime(time);
        setLoading(true);
        
        // Prepare Payment Intent
        fetch('/api/create-payment-intent', {
            method: 'POST',
            body: JSON.stringify({ serviceId: selectedService.id })
        })
        .then(res => res.json())
        .then(data => {
            setClientSecret(data.clientSecret);
            setStep(4);
            setLoading(false);
        });
    };

    const finalizeBooking = (paymentIntent) => {
        setLoading(true);
        fetch('/api/appointments', {
            method: 'POST',
            body: JSON.stringify({
                serviceId: selectedService.id,
                employeeId: selectedEmployee.id,
                date: selectedDate,
                time: selectedTime,
                paymentIntentId: paymentIntent.id
            })
        })
        .then(res => res.json())
        .then(() => {
            setStep(5);
            setBookingComplete(true);
            setLoading(false);
        });
    };

    if (bookingComplete) {
        return (
            <div className="max-w-md mx-auto text-center py-20 animate-fade-in-up">
                <div className="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-500 mx-auto mb-6 border-4 border-white shadow-lg">
                    <span className="material-icons-outlined text-5xl">check_circle</span>
                </div>
                <h2 className="text-3xl font-black text-slate-900 dark:text-white mb-4">¡Cita confirmada!</h2>
                <p className="text-slate-500 mb-8 font-medium">Te hemos enviado un email con los detalles de tu reserva.</p>
                <div className="bg-slate-50 dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 text-left mb-8">
                    <div className="flex justify-between mb-2">
                        <span className="text-slate-400 text-xs font-bold uppercase tracking-wider">Servicio</span>
                        <span className="text-slate-900 dark:text-white font-bold">{selectedService.name}</span>
                    </div>
                    <div className="flex justify-between mb-2">
                        <span className="text-slate-400 text-xs font-bold uppercase tracking-wider">Fecha</span>
                        <span className="text-slate-900 dark:text-white font-bold">{selectedDate} - {selectedTime}</span>
                    </div>
                    <div className="flex justify-between">
                        <span className="text-slate-400 text-xs font-bold uppercase tracking-wider">Profesional</span>
                        <span className="text-slate-900 dark:text-white font-bold">{selectedEmployee.name}</span>
                    </div>
                </div>
                <button onClick={() => window.location.href='/admin'} className="w-full py-4 bg-slate-900 dark:bg-white dark:text-slate-900 text-white rounded-2xl font-bold shadow-xl hover:-translate-y-0.5 transition-all">
                    Volver a Inicio
                </button>
            </div>
        );
    }

    return (
        <div className="flex flex-col lg:flex-row gap-6 lg:gap-10">
            {/* Left Column: Wizard Steps */}
            <div className="w-full lg:w-2/3 flex flex-col gap-8">
                {/* Progress Stepper */}
                <nav aria-label="Progress" className="mb-4">
                    <ol className="flex items-center" role="list">
                        {[1, 2, 3, 4].map((s) => (
                            <li key={s} className={`relative ${s < 4 ? 'pr-8 sm:pr-20 flex-1' : ''}`}>
                                {s < 4 && (
                                    <div aria-hidden="true" className="absolute inset-0 flex items-center">
                                        <div className={`h-0.5 w-full ${step > s ? 'bg-primary' : 'bg-slate-200 dark:bg-slate-700'}`}></div>
                                    </div>
                                )}
                                <button onClick={() => step > s && setStep(s)} className={`relative flex h-8 w-8 items-center justify-center rounded-full transition-all ${step >= s ? 'bg-primary' : 'bg-white dark:bg-slate-800 border-2 border-slate-300'}`}>
                                    {step > s ? (
                                        <span className="material-icons-outlined text-white text-sm">check</span>
                                    ) : (
                                        <span className={`h-2.5 w-2.5 rounded-full ${step === s ? 'bg-white' : 'bg-slate-300'}`}></span>
                                    )}
                                </button>
                                <span className={`absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] font-bold uppercase tracking-widest ${step === s ? 'text-primary' : 'text-slate-500'}`}>
                                    {s === 1 ? 'Servicio' : s === 2 ? 'Estilista' : s === 3 ? 'Fecha' : 'Pago'}
                                </span>
                            </li>
                        ))}
                    </ol>
                </nav>

                <div className="mt-8">
                    {step === 1 && (
                        <div className="space-y-6 animate-fade-in-up">
                            <div>
                                <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-2">Selecciona un servicio</h2>
                                <p className="text-slate-500 dark:text-slate-400 font-medium">Todos nuestros servicios incluyen asesoramiento previo.</p>
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {services.map(service => (
                                    <div 
                                        key={service.id}
                                        onClick={() => handleServiceSelect(service)}
                                        className={`p-6 bg-white dark:bg-slate-900 border-2 rounded-3xl shadow-sm cursor-pointer transition-all hover:shadow-md ${selectedService?.id === service.id ? 'border-primary ring-4 ring-primary/5' : 'border-slate-100 dark:border-slate-800'}`}
                                    >
                                        <div className="flex justify-between items-start mb-4">
                                            <h3 className="font-bold text-slate-900 dark:text-white">{service.name}</h3>
                                            <span className="px-3 py-1 bg-primary/10 text-primary rounded-full text-sm font-bold">{service.price}€</span>
                                        </div>
                                        <p className="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-4 leading-relaxed font-medium">{service.description}</p>
                                        <div className="flex items-center gap-2 text-xs text-slate-400 font-bold uppercase tracking-widest">
                                            <span className="material-icons-outlined text-sm">schedule</span>
                                            {service.duration} min
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {step === 2 && (
                        <div className="space-y-6 animate-fade-in-up">
                            <div>
                                <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-2">Elige a tu estilista</h2>
                                <p className="text-slate-500 dark:text-slate-400 font-medium">Expertos profesionales a tu disposición para cada tipo de cabello.</p>
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {employees.map(employee => (
                                    <div 
                                        key={employee.id}
                                        onClick={() => handleEmployeeSelect(employee)}
                                        className={`group relative flex items-center p-6 bg-white dark:bg-slate-900 border-2 rounded-3xl shadow-sm cursor-pointer transition-all ${selectedEmployee?.id === employee.id ? 'border-primary ring-4 ring-primary/5' : 'border-slate-100 dark:border-slate-800 hover:border-primary/30'}`}
                                    >
                                        <div className="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xl font-bold border-2 border-white dark:border-slate-800 shadow-premium overflow-hidden">
                                            {employee.name.slice(0, 1)}
                                        </div>
                                        <div className="ml-4">
                                            <h3 className="font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors">{employee.name}</h3>
                                            <p className="text-xs text-primary font-bold uppercase tracking-widest mb-1">Colorista Senior</p>
                                            <div className="flex items-center text-amber-400 text-xs">
                                                <span className="material-icons text-sm">star</span>
                                                <span className="material-icons text-sm">star</span>
                                                <span className="material-icons text-sm">star</span>
                                                <span className="material-icons text-sm">star</span>
                                                <span className="material-icons text-sm">star_half</span>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {step === 3 && (
                        <div className="space-y-6 animate-fade-in-up">
                            <div>
                                <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-2">Selecciona horario</h2>
                                <p className="text-slate-500 dark:text-slate-400 font-medium">Disponibilidad en tiempo real para el {selectedDate}.</p>
                            </div>
                            {loading ? (
                                <div className="py-10 text-center animate-pulse">Cargando disponibilidad...</div>
                            ) : (
                                <div className="grid grid-cols-4 sm:grid-cols-6 gap-3">
                                    {availableSlots.map(time => (
                                        <button 
                                            key={time}
                                            onClick={() => handleDateTimeSelect(time)}
                                            className="py-3 px-2 bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-xl font-bold text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary transition-all text-sm"
                                        >
                                            {time}
                                        </button>
                                    ))}
                                </div>
                            )}
                        </div>
                    )}

                    {step === 4 && (
                        <div className="space-y-6 animate-fade-in-up">
                            <div>
                                <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-2">Confirmación y Pago</h2>
                                <p className="text-slate-500 dark:text-slate-400 font-medium">Asegura tu cita abonando una pequeña fianza segura con Stripe.</p>
                            </div>
                            {stripePromise && clientSecret ? (
                                <Elements stripe={stripePromise} options={{ clientSecret }}>
                                    <CheckoutForm 
                                        clientSecret={clientSecret} 
                                        onBack={() => setStep(3)} 
                                        onPaymentSuccess={finalizeBooking}
                                    />
                                </Elements>
                            ) : (
                                <div className="py-10 text-center animate-pulse">Preparando pasarela de pago...</div>
                            )}
                        </div>
                    )}
                </div>
            </div>

            {/* Right Column: Summary Sidebar */}
            <aside className="w-full lg:w-1/3">
                <div className="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800 p-8 sticky top-24">
                    <h2 className="text-lg font-bold text-slate-900 dark:text-white mb-8 flex items-center gap-2">
                        <span className="material-icons-outlined text-primary">receipt_long</span>
                        Tu Reserva
                    </h2>
                    
                    <div className="space-y-6 mb-8">
                        {selectedService && (
                            <div className="flex justify-between items-start animate-fade-in">
                                <div>
                                    <p className="text-sm font-bold text-slate-900 dark:text-white">{selectedService.name}</p>
                                    <p className="text-xs text-slate-500 font-medium uppercase tracking-wider mt-1">{selectedService.duration} min</p>
                                </div>
                                <span className="text-sm font-bold text-slate-900 dark:text-white">{service.price}€</span>
                            </div>
                        )}
                        {selectedEmployee && (
                            <div className="flex items-center gap-3 animate-fade-in">
                                <div className="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-500">
                                    {selectedEmployee.name.slice(0, 1)}
                                </div>
                                <div>
                                    <p className="text-xs text-slate-400 font-bold uppercase tracking-widest">Estilista</p>
                                    <p className="text-sm font-bold text-slate-900 dark:text-white">{selectedEmployee.name}</p>
                                </div>
                            </div>
                        )}
                        {selectedTime && (
                            <div className="flex items-center gap-3 animate-fade-in">
                                <div className="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                    <span className="material-icons-outlined text-sm">calendar_today</span>
                                </div>
                                <div>
                                    <p className="text-xs text-slate-400 font-bold uppercase tracking-widest">Fecha y Hora</p>
                                    <p className="text-sm font-bold text-slate-900 dark:text-white">{selectedDate} - {selectedTime}</p>
                                </div>
                            </div>
                        )}
                    </div>

                    <div className="pt-8 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center mb-8">
                        <span className="text-slate-500 font-bold uppercase tracking-widest text-xs">Total del Servicio</span>
                        <span className="text-3xl font-black text-primary">{selectedService?.price || '0'}€</span>
                    </div>

                    <div className="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-4 mb-4">
                        <div className="flex justify-between items-center mb-1">
                            <span className="text-xs text-slate-500 font-bold uppercase tracking-widest">Fianza Online</span>
                            <span className="text-sm font-bold text-emerald-500">5.00€</span>
                        </div>
                        <p className="text-[10px] text-slate-400 font-medium">Seguro con Stripe. Reembolsable hasta 24h antes.</p>
                    </div>

                    <div className="flex items-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-widest justify-center">
                        <span className="material-icons-outlined text-sm">lock</span>
                        Pago seguro SSL 256-bit
                    </div>
                </div>
            </aside>
        </div>
    );
}
