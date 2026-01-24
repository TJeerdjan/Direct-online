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
    <section className="py-24 bg-slate-50 relative overflow-hidden">
      {/* Background accents */}
      <div className="absolute top-20 left-0 w-72 h-72 bg-teal-400/10 rounded-full blur-3xl" />
      <div className="absolute bottom-20 right-0 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl" />
      
      <div className="container mx-auto px-6 lg:px-12 relative z-10">
        <div className="text-center mb-16">
          <h2 className="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
            {valueStackData.headline}
          </h2>
          <p className="text-xl text-slate-600 max-w-2xl mx-auto">
            {valueStackData.subheadline}
          </p>
        </div>
        
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          {valueStackData.items.map((item, index) => {
            const IconComponent = iconMap[item.icon];
            return (
              <div 
                key={index} 
                className="bg-white rounded-2xl p-8 shadow-lg border border-slate-100 transition-all duration-300 hover:shadow-xl hover:border-teal-200 hover:-translate-y-1 group"
              >
                <div className="w-16 h-16 bg-teal-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-teal-500 transition-colors duration-300">
                  <IconComponent className="w-8 h-8 text-teal-600 group-hover:text-white transition-colors duration-300" />
                </div>
                <h3 className="text-xl font-bold text-slate-900 mb-3">
                  {item.title}
                </h3>
                <p className="text-slate-600 leading-relaxed">
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
