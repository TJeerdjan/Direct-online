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
    <section className="py-24 bg-slate-900 relative overflow-hidden">
      {/* Background effects */}
      <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-teal-900/20 via-slate-900 to-slate-900" />
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-teal-500/10 rounded-full blur-3xl" />
      
      <div className="container mx-auto px-6 lg:px-12 relative z-10">
        <div className="max-w-3xl mx-auto text-center">
          <h2 className="text-4xl md:text-5xl font-bold text-white mb-4">
            {finalCtaData.headline}
          </h2>
          <p className="text-xl text-slate-300 mb-10">
            {finalCtaData.subheadline}
          </p>
          
          {/* CTA Button */}
          <div className="mb-10">
            <Button 
              onClick={handleCTAClick}
              size="lg" 
              className="bg-amber-500 hover:bg-amber-400 text-slate-900 text-xl font-bold px-12 py-8 rounded-2xl shadow-2xl shadow-amber-500/30 transition-all duration-300 hover:scale-105 hover:shadow-amber-500/50 group"
            >
              {finalCtaData.ctaText}
              <ArrowRight className="ml-3 w-6 h-6 transition-transform group-hover:translate-x-2" />
            </Button>
            <p className="mt-4 text-teal-400 font-semibold animate-pulse">
              {finalCtaData.ctaSecondary}
            </p>
          </div>
          
          {/* Benefits */}
          <div className="flex flex-wrap justify-center gap-6">
            {finalCtaData.benefits.map((benefit, index) => {
              const IconComponent = iconMap[benefit] || CheckCircle2;
              return (
                <div key={index} className="flex items-center gap-2 text-slate-300">
                  <IconComponent className="w-5 h-5 text-teal-400" />
                  <span>{benefit}</span>
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
