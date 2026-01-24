import React from 'react';
import { ArrowRight, CheckCircle2, Calendar, Shield, CreditCard } from 'lucide-react';
import { Button } from '../ui/button';
import { finalCtaData, CALENDLY_URL } from '../../data/mock';

const iconMap = {
  "Vrijblijvend gesprek": Calendar,
  "Geen vooruitbetaling": CreditCard,
  "Duidelijke volgende stappen": Shield
};

const FinalCTASection = () => {
  const handleCTAClick = () => {
    window.open(CALENDLY_URL, '_blank');
  };

  return (
    <section className="py-12 sm:py-16 md:py-24 bg-slate-900 relative overflow-hidden">
      {/* Background effects */}
      <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-teal-900/20 via-slate-900 to-slate-900" />
      <div className="hidden sm:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] md:w-[600px] h-[400px] md:h-[600px] bg-teal-500/10 rounded-full blur-3xl" />
      
      <div className="container mx-auto px-4 sm:px-6 lg:px-12 relative z-10">
        <div className="max-w-3xl mx-auto text-center">
          <h2 className="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-3 sm:mb-4">
            {finalCtaData.headline}
          </h2>
          <p className="text-base sm:text-lg md:text-xl text-slate-300 mb-8 sm:mb-10">
            {finalCtaData.subheadline}
          </p>
          
          {/* CTA Button */}
          <div className="mb-8 sm:mb-10">
            <Button 
              onClick={handleCTAClick}
              size="lg" 
              className="w-full sm:w-auto bg-amber-500 hover:bg-amber-400 text-slate-900 text-base sm:text-lg md:text-xl font-bold px-8 sm:px-10 md:px-12 py-6 sm:py-7 md:py-8 rounded-xl sm:rounded-2xl shadow-2xl shadow-amber-500/30 transition-all duration-300 hover:scale-105 hover:shadow-amber-500/50 group"
            >
              {finalCtaData.ctaText}
              <ArrowRight className="ml-2 sm:ml-3 w-5 h-5 sm:w-6 sm:h-6 transition-transform group-hover:translate-x-2" />
            </Button>
            <p className="mt-3 sm:mt-4 text-sm sm:text-base text-teal-400 font-semibold animate-pulse">
              {finalCtaData.ctaSecondary}
            </p>
          </div>
          
          {/* Benefits */}
          <div className="flex flex-col sm:flex-row flex-wrap justify-center gap-4 sm:gap-6">
            {finalCtaData.benefits.map((benefit, index) => {
              const IconComponent = iconMap[benefit] || CheckCircle2;
              return (
                <div key={index} className="flex items-center justify-center gap-2 text-slate-300">
                  <IconComponent className="w-4 h-4 sm:w-5 sm:h-5 text-teal-400 flex-shrink-0" />
                  <span className="text-sm sm:text-base">{benefit}</span>
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </section>
  );
};

export default FinalCTASection;
