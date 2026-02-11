import React, { useState, useEffect } from 'react';

export default function BookingWizard() {
    const [step, setStep] = useState(1);
    const [services, setServices] = useState([]);
    const [employees, setEmployees] = useState([]);
    const [selectedService, setSelectedService] = useState(null);
    const [selectedEmployee, setSelectedEmployee] = useState(null);
    const [selectedDate, setSelectedDate] = useState(null);
    const [selectedTime, setSelectedTime] = useState(null);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        fetch('/api/services')
            .then(res => res.json())
            .then(data => setServices(data));
    }, []);

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
    };

    return (
        <div className="flex flex-col lg:flex-row gap-6 lg:gap-10">
            {/* Left Column: Wizard Steps */}
            <div className="w-full lg:w-2/3 flex flex-col gap-8">
                {/* Progress Stepper */}
                <nav aria-label="Progress" className="mb-4">
                    <ol className="flex items-center" role="list">
                        <li className="relative pr-8 sm:pr-20">
                            <div aria-hidden="true" className="absolute inset-0 flex items-center">
                                <div className={`h-0.5 w-full ${step > 1 ? 'bg-primary' : 'bg-slate-200 dark:bg-slate-700'}`}></div>
                            </div>
                            <button onClick={() => setStep(1)} className={`relative flex h-8 w-8 items-center justify-center rounded-full ${step >= 1 ? 'bg-primary' : 'bg-white dark:bg-slate-800 border-2 border-slate-300'}`}>
                                {step > 1 ? (
                                    <span className="material-icons-outlined text-white text-sm">check</span>
                                ) : (
                                    <span className="h-2.5 w-2.5 rounded-full bg-white"></span>
                                )}
                            </button>
                            <span className={`absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs font-bold ${step === 1 ? 'text-primary' : 'text-slate-500'}`}>Servicios</span>
                        </li>
                        <li className="relative px-8 sm:px-20">
                            <div aria-hidden="true" className="absolute inset-0 flex items-center">
                                <div className={`h-0.5 w-full ${step > 2 ? 'bg-primary' : 'bg-slate-200 dark:bg-slate-700'}`}></div>
                            </div>
                            <button onClick={() => step > 1 && setStep(2)} className={`relative flex h-8 w-8 items-center justify-center rounded-full ${step >= 2 ? 'bg-primary' : 'bg-white dark:bg-slate-800 border-2 border-slate-300'}`}>
                                {step > 2 ? (
                                    <span className="material-icons-outlined text-white text-sm">check</span>
                                ) : (
                                    <span className={`h-2.5 w-2.5 rounded-full ${step === 2 ? 'bg-white' : 'bg-slate-300'}`}></span>
                                )}
                            </button>
                            <span className={`absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs font-bold ${step === 2 ? 'text-primary' : 'text-slate-500'}`}>Profesional</span>
                        </li>
                        <li className="relative pl-8 sm:pl-20">
                            <div aria-hidden="true" className="absolute inset-0 flex items-center">
                                <div className="h-0.5 w-full bg-slate-200 dark:bg-slate-700"></div>
                            </div>
                            <button onClick={() => step > 2 && setStep(3)} className={`relative flex h-8 w-8 items-center justify-center rounded-full ${step >= 3 ? 'bg-primary' : 'bg-white dark:bg-slate-800 border-2 border-slate-300'}`}>
                                <span className={`h-2.5 w-2.5 rounded-full ${step === 3 ? 'bg-white' : 'bg-slate-300'}`}></span>
                            </button>
                            <span className={`absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs font-bold ${step === 3 ? 'text-primary' : 'text-slate-500'}`}>Fecha</span>
                        </li>
                    </ol>
                </nav>

                <div className="mt-8">
                    {step === 1 && (
                        <div className="space-y-6 animate-fade-in-up">
                            <div>
                                <h2 className="text-2xl font-bold text-slate-900 dark:text-white mb-2">Selecciona un servicio</h2>
                                <p className="text-slate-500 dark:text-slate-400">Elige el tratamiento que deseas realizarte.</p>
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {services.map(service => (
                                    <div 
                                        key={service.id}
                                        onClick={() => handleServiceSelect(service)}
                                        className={`p-6 bg-white dark:bg-slate-900 border-2 rounded-2xl shadow-sm cursor-pointer transition-all hover:shadow-md ${selectedService?.id === service.id ? 'border-primary' : 'border-slate-100 dark:border-slate-800'}`}
                                    >
                                        <div className="flex justify-between items-start mb-4">
                                            <h3 className="font-bold text-slate-900 dark:text-white">{service.name}</h3>
                                            <span className="text-lg font-bold text-primary">{service.price}€</span>
                                        </div>
                                        <p className="text-sm text-slate-500 line-clamp-2 mb-4">{service.description}</p>
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
                                <p className="text-slate-500 dark:text-slate-400">Selecciona el profesional con quien deseas realizar tu servicio.</p>
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {employees.map(employee => (
                                    <div 
                                        key={employee.id}
                                        onClick={() => handleEmployeeSelect(employee)}
                                        className={`group relative flex items-center p-4 bg-white dark:bg-slate-900 border-2 rounded-2xl shadow-sm cursor-pointer transition-all ${selectedEmployee?.id === employee.id ? 'border-primary' : 'border-slate-100 dark:border-slate-800 hover:border-primary/30'}`}
                                    >
                                        <div className="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xl font-bold border-2 border-white dark:border-slate-800 shadow-sm overflow-hidden">
                                            {employee.name.slice(0, 1)}
                                        </div>
                                        <div className="ml-4">
                                            <h3 className="font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors">{employee.name}</h3>
                                            <p className="text-xs text-primary font-bold uppercase tracking-widest mb-1">Colorista Senior</p>
                                            <div className="flex items-center text-amber-400 text-xs">
                                                <span className="material-icons-outlined text-sm">star</span>
                                                <span className="material-icons-outlined text-sm">star</span>
                                                <span className="material-icons-outlined text-sm">star</span>
                                                <span className="material-icons-outlined text-sm">star</span>
                                                <span className="material-icons-outlined text-sm">star_half</span>
                                                <span className="ml-1 text-slate-400">(4.8)</span>
                                            </div>
                                        </div>
                                        {selectedEmployee?.id === employee.id && (
                                            <div className="absolute top-4 right-4 h-5 w-5 rounded-full bg-primary flex items-center justify-center">
                                                <span className="material-icons-outlined text-white text-[12px]">check</span>
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {step === 3 && (
                        <div className="space-y-6 animate-fade-in-up text-center py-10">
                            <span className="material-icons-outlined text-6xl text-slate-200 mb-4">event</span>
                            <h2 className="text-2xl font-bold text-slate-900 dark:text-white flex items-center justify-center gap-2">
                                Próximamente: Selector de Fecha
                            </h2>
                            <p className="text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                                Estamos integrando el calendario de disponibilidad real con el backend.
                            </p>
                        </div>
                    )}
                </div>
                
                {/* Navigation Buttons */}
                <div className="flex justify-between pt-8 border-t border-slate-100 dark:border-slate-800 mt-auto">
                    {step > 1 && (
                        <button 
                            onClick={() => setStep(step - 1)}
                            className="px-6 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center gap-2"
                        >
                            <span className="material-icons-outlined text-sm">arrow_back</span> Atrás
                        </button>
                    )}
                    <div className="ml-auto"></div>
                </div>
            </div>

            {/* Right Column: Summary Sidebar */}
            <aside className="w-full lg:w-1/3 flex flex-col gap-6">
                <div className="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800 p-8 sticky top-24">
                    <h2 className="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <span className="material-icons-outlined text-primary">receipt_long</span>
                        Resumen de Reserva
                    </h2>
                    
                    <div className="space-y-6 mb-8">
                        {selectedService ? (
                            <div className="flex justify-between items-start">
                                <div>
                                    <p className="text-sm font-bold text-slate-900 dark:text-white">{selectedService.name}</p>
                                    <p className="text-xs text-slate-500">{selectedService.duration} min • {selectedEmployee?.name || 'Profesional por asignar'}</p>
                                </div>
                                <span className="text-sm font-bold text-slate-900 dark:text-white">{selectedService.price}€</span>
                            </div>
                        ) : (
                            <p className="text-sm text-slate-400 italic">No has seleccionado ningún servicio todavía.</p>
                        )}
                    </div>

                    <div className="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center mb-8">
                        <span className="text-slate-500 font-bold uppercase tracking-widest text-xs">Total estimado</span>
                        <span className="text-3xl font-black text-primary">{selectedService?.price || '0'}€</span>
                    </div>

                    <div className="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-4 flex items-start gap-4 mb-8">
                        <span className="material-icons-outlined text-primary text-sm mt-0.5">info</span>
                        <p className="text-[10px] text-slate-500 leading-relaxed font-medium">
                            El pago se realizará en el establecimiento. Cancelación gratuita hasta 24h antes de la cita.
                        </p>
                    </div>

                    <button 
                        disabled={!selectedService || !selectedEmployee}
                        className={`w-full py-4 rounded-2xl font-bold shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 ${selectedService && selectedEmployee ? 'bg-primary text-white hover:bg-primary-hover hover:-translate-y-0.5' : 'bg-slate-100 text-slate-400 cursor-not-allowed'}`}
                    >
                        Continuar <span className="material-icons-outlined text-sm">arrow_forward</span>
                    </button>
                </div>

                {/* Portfolio Inspiration 2x2 */}
                <div className="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6">
                    <h3 className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Nuestros Trabajos</h3>
                    <div className="grid grid-cols-2 gap-2">
                        <div className="aspect-square rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 hover:opacity-90 transition-opacity">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD67I8TaU4lQALTFFrFLx4E50waxl0cksQipLHSh4oyz9bj6bAaudqsL3BwSIbuUvEbq6Ix1HEg0MrbYL3Uvj_p_0dGe_9jKOLnKFaVxMT93CTtZvk5bJ4LPNNTbEe-pv6PHUwRnSJObWQFiBnWvSTk9nYM2rsBgsZ70VWP4g31bDE75MJ3s20xdIgR6QO_ZmkPxcNjUj5dI-Ot9uWA21qX3siYU7AaLrotLHCO5RhNFCJh0CNL-4oC6wafcr_Gud7rGqFcNaThaA" className="w-full h-full object-cover" />
                        </div>
                        <div className="aspect-square rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 hover:opacity-90 transition-opacity">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBeP9fjjK_a08-7s7dSroEYo2SCqlDDX-DKQqPqAg6tGjrphMc1WCXtfGhnnzrDs7Yrz90blSZI1sBgw58EohZwhh1-VQ88Muzw_WdjZKmp_s4n01AQ-RixVGTJw_wzQviNx5X3EXLEl1Wh_YBSfRMLOFspBaIKQdY3r4t47Mg0CXLp3eSzMqkxXNPBY1dMWCFnch-9tNTK1EHdBiGA483IOVvuboX6R9HAecqeR5IR11RWKP28g_s5iv-k8lK8c_LBrfUhtNyoEw" className="w-full h-full object-cover" />
                        </div>
                        <div className="aspect-square rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 hover:opacity-90 transition-opacity">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAV7WUREt-bXdnQc-beskW7u4rTznUqm7-hoTIIKMufWoYk8OIP4GG22laSrv-2lBSj5JQEkX5aGr8WSgcWbnHxSeFRWYTdcr_Elou5ynj1JoJp_whnaqoBwmdVmspN_NZ6vFz_am4BlPXj1oIOC1LGf6P2-bKLvI1H6jXUfbyt4n_L2lMedELyjTcJlwAZXn4zvQh3dHHdt1Uohg0McKuElRjnHqMdb4oJQ3kwOzYoRBMNJhRTc1IG1qYY0d6yzE6545C3q2WAvw" className="w-full h-full object-cover" />
                        </div>
                        <div className="aspect-square rounded-2xl bg-primary/5 flex items-center justify-center text-center p-2 cursor-pointer hover:bg-primary/10 transition-colors">
                            <span className="text-[10px] font-bold text-primary">Ver más inspiración</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    );
}
