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
    <section className="py-24 bg-white relative">
      <div className="container mx-auto px-6 lg:px-12">
        <div className="max-w-4xl mx-auto">
          <div className="text-center mb-16">
            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
              {whyPossibleData.headline}
            </h2>
            <p className="text-xl text-slate-600">
              {whyPossibleData.subheadline}
            </p>
          </div>
          
          <div className="space-y-8">
            {whyPossibleData.points.map((point, index) => {
              const IconComponent = iconMap[point.icon];
              return (
                <div 
                  key={index} 
                  className="flex gap-6 items-start bg-slate-50 rounded-2xl p-8 border border-slate-100 transition-all duration-300 hover:border-teal-200 hover:shadow-lg"
                >
                  <div className="flex-shrink-0 w-16 h-16 bg-teal-500 rounded-2xl flex items-center justify-center shadow-lg shadow-teal-500/30">
                    <IconComponent className="w-8 h-8 text-white" />
                  </div>
                  <div>
                    <h3 className="text-xl font-bold text-slate-900 mb-2">
                      {point.title}
                    </h3>
                    <p className="text-slate-600 leading-relaxed">
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
