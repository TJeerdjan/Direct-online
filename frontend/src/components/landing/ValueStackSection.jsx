import React from 'react';
import { Globe, Target, Pen, BarChart3, FileText, Headphones } from 'lucide-react';
import { valueStackData } from '../../data/mock';

const iconMap = {
  Globe,
  Target,
  Pen,
  BarChart3,
  FileText,
  HeadphonesIcon: Headphones
};

const ValueStackSection = () => {
  return (
    <section className="py-12 sm:py-16 md:py-24 bg-slate-50 relative overflow-hidden">
      {/* Background accents */}
      <div className="hidden sm:block absolute top-20 left-0 w-48 md:w-72 h-48 md:h-72 bg-teal-400/10 rounded-full blur-3xl" />
      <div className="hidden sm:block absolute bottom-20 right-0 w-64 md:w-96 h-64 md:h-96 bg-amber-400/10 rounded-full blur-3xl" />
      
      <div className="container mx-auto px-4 sm:px-6 lg:px-12 relative z-10">
        <div className="text-center mb-10 sm:mb-16">
          <h2 className="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 mb-3 sm:mb-4 px-2">
            {valueStackData.headline}
          </h2>
          <p className="text-base sm:text-lg md:text-xl text-slate-600 max-w-2xl mx-auto px-4">
            {valueStackData.subheadline}
          </p>
        </div>
        
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
          {valueStackData.items.map((item, index) => {
            const IconComponent = iconMap[item.icon];
            return (
              <div 
                key={index} 
                className="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-lg border border-slate-100 transition-all duration-300 hover:shadow-xl hover:border-teal-200 hover:-translate-y-1 group"
              >
                <div className="w-12 h-12 sm:w-14 md:w-16 sm:h-14 md:h-16 bg-teal-100 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 group-hover:bg-teal-500 transition-colors duration-300">
                  <IconComponent className="w-6 h-6 sm:w-7 md:w-8 sm:h-7 md:h-8 text-teal-600 group-hover:text-white transition-colors duration-300" />
                </div>
                <h3 className="text-lg sm:text-xl font-bold text-slate-900 mb-2 sm:mb-3">
                  {item.title}
                </h3>
                <p className="text-sm sm:text-base text-slate-600 leading-relaxed">
                  {item.description}
                </p>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
};

export default ValueStackSection;
