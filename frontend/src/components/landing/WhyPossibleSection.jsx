import React from 'react';
import { Cpu, MapPin, Handshake } from 'lucide-react';
import { whyPossibleData } from '../../data/mock';

const iconMap = {
  Cpu,
  MapPin,
  Handshake
};

const WhyPossibleSection = () => {
  return (
    <section className="py-12 sm:py-16 md:py-24 bg-white relative">
      <div className="container mx-auto px-4 sm:px-6 lg:px-12">
        <div className="max-w-4xl mx-auto">
          <div className="text-center mb-10 sm:mb-16">
            <h2 className="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 mb-3 sm:mb-4">
              {whyPossibleData.headline}
            </h2>
            <p className="text-base sm:text-lg md:text-xl text-slate-600 px-4">
              {whyPossibleData.subheadline}
            </p>
          </div>
          
          <div className="space-y-4 sm:space-y-6 md:space-y-8">
            {whyPossibleData.points.map((point, index) => {
              const IconComponent = iconMap[point.icon];
              return (
                <div 
                  key={index} 
                  className="flex flex-col sm:flex-row gap-4 sm:gap-6 items-start bg-slate-50 rounded-xl sm:rounded-2xl p-5 sm:p-6 md:p-8 border border-slate-100 transition-all duration-300 hover:border-teal-200 hover:shadow-lg"
                >
                  <div className="flex-shrink-0 w-12 h-12 sm:w-14 md:w-16 sm:h-14 md:h-16 bg-teal-500 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg shadow-teal-500/30">
                    <IconComponent className="w-6 h-6 sm:w-7 md:w-8 sm:h-7 md:h-8 text-white" />
                  </div>
                  <div>
                    <h3 className="text-lg sm:text-xl font-bold text-slate-900 mb-2">
                      {point.title}
                    </h3>
                    <p className="text-sm sm:text-base text-slate-600 leading-relaxed">
                      {point.description}
                    </p>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </section>
  );
};

export default WhyPossibleSection;
