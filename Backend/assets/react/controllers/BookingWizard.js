import React, { useState, useEffect } from 'react';
import { loadStripe } from '@stripe/stripe-js';
import { Elements, CardElement, useStripe, useElements } from '@stripe/react-stripe-js';
const CheckoutForm = ({
  clientSecret,
  onPaymentSuccess,
  onBack,
  amount
}) => {
  const stripe = useStripe();
  const elements = useElements();
  const [error, setError] = useState(null);
  const [processing, setProcessing] = useState(false);
  const handleSubmit = async event => {
    event.preventDefault();
    setProcessing(true);
    if (!stripe || !elements) return;
    const payload = await stripe.confirmCardPayment(clientSecret, {
      payment_method: {
        card: elements.getElement(CardElement)
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
  return /*#__PURE__*/React.createElement("form", {
    onSubmit: handleSubmit,
    className: "space-y-6 animate-fade-in-up"
  }, /*#__PURE__*/React.createElement("div", {
    className: "bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-100 dark:border-slate-700"
  }, /*#__PURE__*/React.createElement("h3", {
    className: "text-lg font-bold text-slate-900 dark:text-white mb-4"
  }, "Informaci\xF3n de Pago"), /*#__PURE__*/React.createElement("div", {
    className: "p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl shadow-inner mb-4"
  }, /*#__PURE__*/React.createElement(CardElement, {
    options: {
      style: {
        base: {
          fontSize: '16px',
          color: '#424770',
          '::placeholder': {
            color: '#aab7c4'
          }
        },
        invalid: {
          color: '#9e2146'
        }
      }
    }
  })), error && /*#__PURE__*/React.createElement("div", {
    className: "text-red-500 text-sm font-bold mb-4"
  }, error), /*#__PURE__*/React.createElement("p", {
    className: "text-xs text-slate-500 mb-6 font-medium"
  }, "Se realizar\xE1 un cargo de ", /*#__PURE__*/React.createElement("span", {
    className: "text-primary font-bold"
  }, "5.00\u20AC"), " en concepto de fianza para confirmar tu reserva. El resto se abonar\xE1 en el sal\xF3n.")), /*#__PURE__*/React.createElement("div", {
    className: "flex justify-between gap-4"
  }, /*#__PURE__*/React.createElement("button", {
    type: "button",
    onClick: onBack,
    className: "px-6 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-bold hover:bg-slate-50 transition-colors"
  }, "Atr\xE1s"), /*#__PURE__*/React.createElement("button", {
    disabled: processing || !stripe,
    className: `flex-1 py-4 rounded-xl font-bold text-white shadow-lg transition-all ${processing ? 'bg-slate-400' : 'bg-primary hover:bg-primary-hover shadow-primary/20'}`
  }, processing ? 'Procesando...' : `Pagar 5.00€ y Confirmar`)));
};
export default function BookingWizard({
  stripePublicKey
}) {
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
    fetch('/api/services').then(res => res.json()).then(data => setServices(data));
  }, []);
  const fetchSlots = () => {
    setLoading(true);
    fetch('/api/availability').then(res => res.json()).then(data => {
      setAvailableSlots(data.slots);
      setLoading(false);
    });
  };
  const handleServiceSelect = service => {
    setSelectedService(service);
    setStep(2);
    setLoading(true);
    fetch(`/api/employees?service=${service.id}`).then(res => res.json()).then(data => {
      setEmployees(data);
      setLoading(false);
    });
  };
  const handleEmployeeSelect = employee => {
    setSelectedEmployee(employee);
    setStep(3);
    fetchSlots();
  };
  const handleDateTimeSelect = time => {
    setSelectedTime(time);
    setLoading(true);

    // Prepare Payment Intent
    fetch('/api/create-payment-intent', {
      method: 'POST',
      body: JSON.stringify({
        serviceId: selectedService.id
      })
    }).then(res => res.json()).then(data => {
      setClientSecret(data.clientSecret);
      setStep(4);
      setLoading(false);
    });
  };
  const finalizeBooking = paymentIntent => {
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
    }).then(res => res.json()).then(() => {
      setStep(5);
      setBookingComplete(true);
      setLoading(false);
    });
  };
  if (bookingComplete) {
    return /*#__PURE__*/React.createElement("div", {
      className: "max-w-md mx-auto text-center py-20 animate-fade-in-up"
    }, /*#__PURE__*/React.createElement("div", {
      className: "w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-500 mx-auto mb-6 border-4 border-white shadow-lg"
    }, /*#__PURE__*/React.createElement("span", {
      className: "material-icons-outlined text-5xl"
    }, "check_circle")), /*#__PURE__*/React.createElement("h2", {
      className: "text-3xl font-black text-slate-900 dark:text-white mb-4"
    }, "\xA1Cita confirmada!"), /*#__PURE__*/React.createElement("p", {
      className: "text-slate-500 mb-8 font-medium"
    }, "Te hemos enviado un email con los detalles de tu reserva."), /*#__PURE__*/React.createElement("div", {
      className: "bg-slate-50 dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800 text-left mb-8"
    }, /*#__PURE__*/React.createElement("div", {
      className: "flex justify-between mb-2"
    }, /*#__PURE__*/React.createElement("span", {
      className: "text-slate-400 text-xs font-bold uppercase tracking-wider"
    }, "Servicio"), /*#__PURE__*/React.createElement("span", {
      className: "text-slate-900 dark:text-white font-bold"
    }, selectedService.name)), /*#__PURE__*/React.createElement("div", {
      className: "flex justify-between mb-2"
    }, /*#__PURE__*/React.createElement("span", {
      className: "text-slate-400 text-xs font-bold uppercase tracking-wider"
    }, "Fecha"), /*#__PURE__*/React.createElement("span", {
      className: "text-slate-900 dark:text-white font-bold"
    }, selectedDate, " - ", selectedTime)), /*#__PURE__*/React.createElement("div", {
      className: "flex justify-between"
    }, /*#__PURE__*/React.createElement("span", {
      className: "text-slate-400 text-xs font-bold uppercase tracking-wider"
    }, "Profesional"), /*#__PURE__*/React.createElement("span", {
      className: "text-slate-900 dark:text-white font-bold"
    }, selectedEmployee.name))), /*#__PURE__*/React.createElement("button", {
      onClick: () => window.location.href = '/admin',
      className: "w-full py-4 bg-slate-900 dark:bg-white dark:text-slate-900 text-white rounded-2xl font-bold shadow-xl hover:-translate-y-0.5 transition-all"
    }, "Volver a Inicio"));
  }
  return /*#__PURE__*/React.createElement("div", {
    className: "flex flex-col lg:flex-row gap-6 lg:gap-10"
  }, /*#__PURE__*/React.createElement("div", {
    className: "w-full lg:w-2/3 flex flex-col gap-8"
  }, /*#__PURE__*/React.createElement("nav", {
    "aria-label": "Progress",
    className: "mb-4"
  }, /*#__PURE__*/React.createElement("ol", {
    className: "flex items-center",
    role: "list"
  }, [1, 2, 3, 4].map(s => /*#__PURE__*/React.createElement("li", {
    key: s,
    className: `relative ${s < 4 ? 'pr-8 sm:pr-20 flex-1' : ''}`
  }, s < 4 && /*#__PURE__*/React.createElement("div", {
    "aria-hidden": "true",
    className: "absolute inset-0 flex items-center"
  }, /*#__PURE__*/React.createElement("div", {
    className: `h-0.5 w-full ${step > s ? 'bg-primary' : 'bg-slate-200 dark:bg-slate-700'}`
  })), /*#__PURE__*/React.createElement("button", {
    onClick: () => step > s && setStep(s),
    className: `relative flex h-8 w-8 items-center justify-center rounded-full transition-all ${step >= s ? 'bg-primary' : 'bg-white dark:bg-slate-800 border-2 border-slate-300'}`
  }, step > s ? /*#__PURE__*/React.createElement("span", {
    className: "material-icons-outlined text-white text-sm"
  }, "check") : /*#__PURE__*/React.createElement("span", {
    className: `h-2.5 w-2.5 rounded-full ${step === s ? 'bg-white' : 'bg-slate-300'}`
  })), /*#__PURE__*/React.createElement("span", {
    className: `absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] font-bold uppercase tracking-widest ${step === s ? 'text-primary' : 'text-slate-500'}`
  }, s === 1 ? 'Servicio' : s === 2 ? 'Estilista' : s === 3 ? 'Fecha' : 'Pago'))))), /*#__PURE__*/React.createElement("div", {
    className: "mt-8"
  }, step === 1 && /*#__PURE__*/React.createElement("div", {
    className: "space-y-6 animate-fade-in-up"
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h2", {
    className: "text-2xl font-bold text-slate-900 dark:text-white mb-2"
  }, "Selecciona un servicio"), /*#__PURE__*/React.createElement("p", {
    className: "text-slate-500 dark:text-slate-400 font-medium"
  }, "Todos nuestros servicios incluyen asesoramiento previo.")), /*#__PURE__*/React.createElement("div", {
    className: "grid grid-cols-1 sm:grid-cols-2 gap-4"
  }, services.map(service => /*#__PURE__*/React.createElement("div", {
    key: service.id,
    onClick: () => handleServiceSelect(service),
    className: `p-6 bg-white dark:bg-slate-900 border-2 rounded-3xl shadow-sm cursor-pointer transition-all hover:shadow-md ${selectedService?.id === service.id ? 'border-primary ring-4 ring-primary/5' : 'border-slate-100 dark:border-slate-800'}`
  }, /*#__PURE__*/React.createElement("div", {
    className: "flex justify-between items-start mb-4"
  }, /*#__PURE__*/React.createElement("h3", {
    className: "font-bold text-slate-900 dark:text-white"
  }, service.name), /*#__PURE__*/React.createElement("span", {
    className: "px-3 py-1 bg-primary/10 text-primary rounded-full text-sm font-bold"
  }, service.price, "\u20AC")), /*#__PURE__*/React.createElement("p", {
    className: "text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-4 leading-relaxed font-medium"
  }, service.description), /*#__PURE__*/React.createElement("div", {
    className: "flex items-center gap-2 text-xs text-slate-400 font-bold uppercase tracking-widest"
  }, /*#__PURE__*/React.createElement("span", {
    className: "material-icons-outlined text-sm"
  }, "schedule"), service.duration, " min"))))), step === 2 && /*#__PURE__*/React.createElement("div", {
    className: "space-y-6 animate-fade-in-up"
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h2", {
    className: "text-2xl font-bold text-slate-900 dark:text-white mb-2"
  }, "Elige a tu estilista"), /*#__PURE__*/React.createElement("p", {
    className: "text-slate-500 dark:text-slate-400 font-medium"
  }, "Expertos profesionales a tu disposici\xF3n para cada tipo de cabello.")), /*#__PURE__*/React.createElement("div", {
    className: "grid grid-cols-1 sm:grid-cols-2 gap-4"
  }, employees.map(employee => /*#__PURE__*/React.createElement("div", {
    key: employee.id,
    onClick: () => handleEmployeeSelect(employee),
    className: `group relative flex items-center p-6 bg-white dark:bg-slate-900 border-2 rounded-3xl shadow-sm cursor-pointer transition-all ${selectedEmployee?.id === employee.id ? 'border-primary ring-4 ring-primary/5' : 'border-slate-100 dark:border-slate-800 hover:border-primary/30'}`
  }, /*#__PURE__*/React.createElement("div", {
    className: "w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xl font-bold border-2 border-white dark:border-slate-800 shadow-premium overflow-hidden"
  }, employee.name.slice(0, 1)), /*#__PURE__*/React.createElement("div", {
    className: "ml-4"
  }, /*#__PURE__*/React.createElement("h3", {
    className: "font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors"
  }, employee.name), /*#__PURE__*/React.createElement("p", {
    className: "text-xs text-primary font-bold uppercase tracking-widest mb-1"
  }, "Colorista Senior"), /*#__PURE__*/React.createElement("div", {
    className: "flex items-center text-amber-400 text-xs"
  }, /*#__PURE__*/React.createElement("span", {
    className: "material-icons text-sm"
  }, "star"), /*#__PURE__*/React.createElement("span", {
    className: "material-icons text-sm"
  }, "star"), /*#__PURE__*/React.createElement("span", {
    className: "material-icons text-sm"
  }, "star"), /*#__PURE__*/React.createElement("span", {
    className: "material-icons text-sm"
  }, "star"), /*#__PURE__*/React.createElement("span", {
    className: "material-icons text-sm"
  }, "star_half"))))))), step === 3 && /*#__PURE__*/React.createElement("div", {
    className: "space-y-6 animate-fade-in-up"
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h2", {
    className: "text-2xl font-bold text-slate-900 dark:text-white mb-2"
  }, "Selecciona horario"), /*#__PURE__*/React.createElement("p", {
    className: "text-slate-500 dark:text-slate-400 font-medium"
  }, "Disponibilidad en tiempo real para el ", selectedDate, ".")), loading ? /*#__PURE__*/React.createElement("div", {
    className: "py-10 text-center animate-pulse"
  }, "Cargando disponibilidad...") : /*#__PURE__*/React.createElement("div", {
    className: "grid grid-cols-4 sm:grid-cols-6 gap-3"
  }, availableSlots.map(time => /*#__PURE__*/React.createElement("button", {
    key: time,
    onClick: () => handleDateTimeSelect(time),
    className: "py-3 px-2 bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-xl font-bold text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary transition-all text-sm"
  }, time)))), step === 4 && /*#__PURE__*/React.createElement("div", {
    className: "space-y-6 animate-fade-in-up"
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("h2", {
    className: "text-2xl font-bold text-slate-900 dark:text-white mb-2"
  }, "Confirmaci\xF3n y Pago"), /*#__PURE__*/React.createElement("p", {
    className: "text-slate-500 dark:text-slate-400 font-medium"
  }, "Asegura tu cita abonando una peque\xF1a fianza segura con Stripe.")), stripePromise && clientSecret ? /*#__PURE__*/React.createElement(Elements, {
    stripe: stripePromise,
    options: {
      clientSecret
    }
  }, /*#__PURE__*/React.createElement(CheckoutForm, {
    clientSecret: clientSecret,
    onBack: () => setStep(3),
    onPaymentSuccess: finalizeBooking
  })) : /*#__PURE__*/React.createElement("div", {
    className: "py-10 text-center animate-pulse"
  }, "Preparando pasarela de pago...")))), /*#__PURE__*/React.createElement("aside", {
    className: "w-full lg:w-1/3"
  }, /*#__PURE__*/React.createElement("div", {
    className: "bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800 p-8 sticky top-24"
  }, /*#__PURE__*/React.createElement("h2", {
    className: "text-lg font-bold text-slate-900 dark:text-white mb-8 flex items-center gap-2"
  }, /*#__PURE__*/React.createElement("span", {
    className: "material-icons-outlined text-primary"
  }, "receipt_long"), "Tu Reserva"), /*#__PURE__*/React.createElement("div", {
    className: "space-y-6 mb-8"
  }, selectedService && /*#__PURE__*/React.createElement("div", {
    className: "flex justify-between items-start animate-fade-in"
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
    className: "text-sm font-bold text-slate-900 dark:text-white"
  }, selectedService.name), /*#__PURE__*/React.createElement("p", {
    className: "text-xs text-slate-500 font-medium uppercase tracking-wider mt-1"
  }, selectedService.duration, " min")), /*#__PURE__*/React.createElement("span", {
    className: "text-sm font-bold text-slate-900 dark:text-white"
  }, service.price, "\u20AC")), selectedEmployee && /*#__PURE__*/React.createElement("div", {
    className: "flex items-center gap-3 animate-fade-in"
  }, /*#__PURE__*/React.createElement("div", {
    className: "w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-500"
  }, selectedEmployee.name.slice(0, 1)), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
    className: "text-xs text-slate-400 font-bold uppercase tracking-widest"
  }, "Estilista"), /*#__PURE__*/React.createElement("p", {
    className: "text-sm font-bold text-slate-900 dark:text-white"
  }, selectedEmployee.name))), selectedTime && /*#__PURE__*/React.createElement("div", {
    className: "flex items-center gap-3 animate-fade-in"
  }, /*#__PURE__*/React.createElement("div", {
    className: "w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary"
  }, /*#__PURE__*/React.createElement("span", {
    className: "material-icons-outlined text-sm"
  }, "calendar_today")), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("p", {
    className: "text-xs text-slate-400 font-bold uppercase tracking-widest"
  }, "Fecha y Hora"), /*#__PURE__*/React.createElement("p", {
    className: "text-sm font-bold text-slate-900 dark:text-white"
  }, selectedDate, " - ", selectedTime)))), /*#__PURE__*/React.createElement("div", {
    className: "pt-8 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center mb-8"
  }, /*#__PURE__*/React.createElement("span", {
    className: "text-slate-500 font-bold uppercase tracking-widest text-xs"
  }, "Total del Servicio"), /*#__PURE__*/React.createElement("span", {
    className: "text-3xl font-black text-primary"
  }, selectedService?.price || '0', "\u20AC")), /*#__PURE__*/React.createElement("div", {
    className: "bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-4 mb-4"
  }, /*#__PURE__*/React.createElement("div", {
    className: "flex justify-between items-center mb-1"
  }, /*#__PURE__*/React.createElement("span", {
    className: "text-xs text-slate-500 font-bold uppercase tracking-widest"
  }, "Fianza Online"), /*#__PURE__*/React.createElement("span", {
    className: "text-sm font-bold text-emerald-500"
  }, "5.00\u20AC")), /*#__PURE__*/React.createElement("p", {
    className: "text-[10px] text-slate-400 font-medium"
  }, "Seguro con Stripe. Reembolsable hasta 24h antes.")), /*#__PURE__*/React.createElement("div", {
    className: "flex items-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-widest justify-center"
  }, /*#__PURE__*/React.createElement("span", {
    className: "material-icons-outlined text-sm"
  }, "lock"), "Pago seguro SSL 256-bit"))));
}
