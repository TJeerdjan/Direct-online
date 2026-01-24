import React from 'react';
import { Users, CheckCircle2 } from 'lucide-react';
import { personalApproachData } from '../../data/mock';

const PersonalApproachSection = () => {
  return (
    <section className="py-12 sm:py-16 md:py-24 bg-slate-900 text-white relative overflow-hidden">
      {/* Background accents */}
      <div className="hidden sm:block absolute top-0 left-1/4 w-64 md:w-96 h-64 md:h-96 bg-teal-500/10 rounded-full blur-3xl" />
      <div className="hidden sm:block absolute bottom-0 right-1/4 w-48 md:w-72 h-48 md:h-72 bg-amber-500/10 rounded-full blur-3xl" />
      
      <div className="container mx-auto px-4 sm:px-6 lg:px-12 relative z-10">
        <div className="max-w-4xl mx-auto">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
            {/* Content */}
            <div className="order-2 lg:order-1">
              <div className="inline-flex items-center gap-2 bg-teal-500/20 text-teal-300 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full mb-4 sm:mb-6">
                <Users className="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                <span className="text-xs sm:text-sm font-semibold">Persoonlijke aanpak</span>
              </div>
              
              <h2 className="text-2xl sm:text-3xl md:text-4xl font-bold mb-3 sm:mb-4">
                {personalApproachData.headline}
              </h2>
              <p className="text-lg sm:text-xl text-slate-300 mb-4 sm:mb-6">
                {personalApproachData.subheadline}
              </p>
              <p className="text-sm sm:text-base text-slate-400 leading-relaxed mb-6 sm:mb-8">
                {personalApproachData.description}
              </p>
              
              <div className="space-y-3 sm:space-y-4">
                {personalApproachData.benefits.map((benefit, index) => (
                  <div key={index} className="flex items-center gap-3">
                    <CheckCircle2 className="w-5 h-5 sm:w-6 sm:h-6 text-teal-400 flex-shrink-0" />
                    <span className="text-base sm:text-lg text-white">{benefit}</span>
                  </div>
                ))}
              </div>
            </div>
            
            {/* Visual element */}
            <div className="relative order-1 lg:order-2">
              <div className="bg-slate-800 rounded-2xl sm:rounded-3xl p-5 sm:p-6 md:p-8 border border-slate-700">
                <div className="aspect-square bg-slate-700/50 rounded-xl sm:rounded-2xl flex items-center justify-center relative overflow-hidden">
                  {/* Office illustration */}
                  <div className="text-center">
                    <div className="w-20 h-20 sm:w-28 md:w-32 sm:h-28 md:h-32 bg-teal-500/20 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                      <Users className="w-10 h-10 sm:w-14 md:w-16 sm:h-14 md:h-16 text-teal-400" />
                    </div>
                    <p className="text-lg sm:text-xl font-semibold text-white">Face-to-face</p>
                    <p className="text-sm sm:text-base text-slate-400">bij jou op kantoor</p>
                  </div>
                  
                  {/* Decorative elements */}
                  <div className="absolute top-4 right-4 w-8 sm:w-12 h-8 sm:h-12 bg-amber-500/20 rounded-full blur-xl" />
                  <div className="absolute bottom-4 left-4 w-10 sm:w-16 h-10 sm:h-16 bg-teal-500/20 rounded-full blur-xl" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default PersonalApproachSection;
