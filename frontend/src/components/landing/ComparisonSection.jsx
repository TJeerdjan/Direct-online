import React from 'react';
import { Check, X, ArrowRight } from 'lucide-react';
import { Button } from '../ui/button';
import { comparisonData, CALENDLY_URL } from '../../data/mock';

const ComparisonSection = () => {
  const handleCTAClick = () => {
    window.open(CALENDLY_URL, '_blank');
  };

  return (
    <section className="py-12 sm:py-16 md:py-24 bg-white relative">
      <div className="container mx-auto px-4 sm:px-6 lg:px-12">
        <div className="text-center mb-8 sm:mb-12 md:mb-16">
          <h2 className="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 mb-3 sm:mb-4 px-2">
            {comparisonData.headline}
          </h2>
          <p className="text-lg sm:text-xl text-teal-600 font-semibold">
            {comparisonData.subheadline}
          </p>
        </div>
        
        {/* Comparison Table - Desktop */}
        <div className="hidden md:block max-w-4xl mx-auto">
          <div className="bg-slate-50 rounded-3xl overflow-hidden shadow-xl border border-slate-200">
            {/* Table Header */}
            <div className="grid grid-cols-3 bg-slate-100">
              <div className="p-4 lg:p-6 border-r border-slate-200">
                <span className="text-xs lg:text-sm font-medium text-slate-500 uppercase tracking-wider">Feature</span>
              </div>
              <div className="p-4 lg:p-6 border-r border-slate-200 text-center bg-slate-200">
                <span className="text-xs lg:text-sm font-medium text-slate-600 uppercase tracking-wider">Traditioneel Bureau</span>
              </div>
              <div className="p-4 lg:p-6 text-center bg-teal-500">
                <span className="text-xs lg:text-sm font-bold text-white uppercase tracking-wider">direct-online</span>
              </div>
            </div>
            
            {/* Table Body */}
            {comparisonData.items.map((item, index) => (
              <div 
                key={index} 
                className={`grid grid-cols-3 ${index % 2 === 0 ? 'bg-white' : 'bg-slate-50'} border-t border-slate-200`}
              >
                <div className="p-4 lg:p-5 border-r border-slate-200 flex items-center">
                  <span className="text-sm lg:text-base font-medium text-slate-700">{item.feature}</span>
                </div>
                <div className="p-4 lg:p-5 border-r border-slate-200 flex items-center justify-center">
                  <span className="text-sm lg:text-base text-slate-500 flex items-center gap-2">
                    <X className="w-4 h-4 text-red-400 flex-shrink-0" />
                    <span className="text-center">{item.agency}</span>
                  </span>
                </div>
                <div className="p-4 lg:p-5 flex items-center justify-center bg-teal-50">
                  <span className="text-sm lg:text-base text-teal-700 font-semibold flex items-center gap-2">
                    <Check className="w-4 h-4 lg:w-5 lg:h-5 text-teal-500 flex-shrink-0" />
                    {item.directOnline}
                  </span>
                </div>
              </div>
            ))}
            
            {/* Total Row */}
            <div className="grid grid-cols-3 border-t-2 border-slate-300">
              <div className="p-4 lg:p-6 border-r border-slate-200 bg-slate-100">
                <span className="text-base lg:text-lg font-bold text-slate-900">TOTAAL</span>
              </div>
              <div className="p-4 lg:p-6 border-r border-slate-200 bg-slate-200 text-center">
                <span className="text-lg lg:text-2xl font-bold text-slate-600 line-through">{comparisonData.totalAgency}</span>
              </div>
              <div className="p-4 lg:p-6 bg-teal-500 text-center">
                <span className="text-lg lg:text-2xl font-black text-white">{comparisonData.totalDirectOnline}</span>
              </div>
            </div>
          </div>
        </div>

        {/* Comparison Cards - Mobile/Tablet */}
        <div className="md:hidden space-y-4">
          {comparisonData.items.map((item, index) => (
            <div key={index} className="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
              <div className="bg-slate-100 px-4 py-3 border-b border-slate-200">
                <span className="font-semibold text-slate-800">{item.feature}</span>
              </div>
              <div className="grid grid-cols-2">
                <div className="p-4 border-r border-slate-200 bg-slate-50">
                  <p className="text-xs text-slate-500 mb-1">Traditioneel</p>
                  <div className="flex items-center gap-1.5">
                    <X className="w-4 h-4 text-red-400 flex-shrink-0" />
                    <span className="text-sm text-slate-600">{item.agency}</span>
                  </div>
                </div>
                <div className="p-4 bg-teal-50">
                  <p className="text-xs text-teal-600 mb-1">direct-online</p>
                  <div className="flex items-center gap-1.5">
                    <Check className="w-4 h-4 text-teal-500 flex-shrink-0" />
                    <span className="text-sm font-semibold text-teal-700">{item.directOnline}</span>
                  </div>
                </div>
              </div>
            </div>
          ))}
          
          {/* Total Card - Mobile */}
          <div className="bg-slate-900 rounded-xl overflow-hidden mt-6">
            <div className="grid grid-cols-2">
              <div className="p-4 border-r border-slate-700">
                <p className="text-xs text-slate-400 mb-1">Traditioneel bureau</p>
                <span className="text-xl font-bold text-slate-400 line-through">{comparisonData.totalAgency}</span>
              </div>
              <div className="p-4 bg-teal-500">
                <p className="text-xs text-teal-100 mb-1">direct-online</p>
                <span className="text-xl font-black text-white">{comparisonData.totalDirectOnline}</span>
              </div>
            </div>
          </div>
        </div>
        
        {/* CTA under table */}
        <div className="text-center mt-8 sm:mt-12">
          <Button 
            onClick={handleCTAClick}
            size="lg" 
            className="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white text-base sm:text-lg px-8 sm:px-10 py-5 sm:py-6 rounded-xl shadow-lg shadow-amber-500/30 transition-all duration-300 hover:scale-105 group"
          >
            Bekijk beschikbaarheid
            <ArrowRight className="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" />
          </Button>
        </div>
      </div>
    </section>
  );
};

export default ComparisonSection;
