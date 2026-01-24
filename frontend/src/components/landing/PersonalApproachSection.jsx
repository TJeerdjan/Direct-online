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
              <div className="rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl border border-slate-700">
                <img 
                  src="https://customer-assets.emergentagent.com/job_quick-website-pro/artifacts/tiq0q411_Untitled%20design%20%2874%29.png"
                  alt="Face-to-face gesprek bij jou op kantoor"
                  className="w-full h-auto object-cover"
                />
                {/* Overlay with text */}
                <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/90 via-slate-900/60 to-transparent p-4 sm:p-6">
                  <p className="text-lg sm:text-xl font-semibold text-white">Face-to-face</p>
                  <p className="text-sm sm:text-base text-slate-300">bij jou op kantoor</p>
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
