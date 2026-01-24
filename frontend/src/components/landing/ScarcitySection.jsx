import React from 'react';
import { AlertTriangle, Clock, Lock } from 'lucide-react';
import { scarcityData } from '../../data/mock';

const ScarcitySection = () => {
  return (
    <section className="py-12 sm:py-16 md:py-24 bg-amber-50 relative overflow-hidden">
      {/* Warning pattern overlay */}
      <div className="absolute inset-0 opacity-5">
        <div className="absolute inset-0" style={{
          backgroundImage: 'repeating-linear-gradient(45deg, transparent, transparent 20px, #f59e0b 20px, #f59e0b 22px)'
        }} />
      </div>
      
      <div className="container mx-auto px-4 sm:px-6 lg:px-12 relative z-10">
        <div className="max-w-3xl mx-auto text-center">
          {/* Alert badge */}
          <div className="inline-flex items-center gap-2 bg-amber-500 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-full mb-6 sm:mb-8 shadow-lg shadow-amber-500/30">
            <AlertTriangle className="w-4 h-4 sm:w-5 sm:h-5" />
            <span className="text-sm sm:text-base font-bold">BEPERKT AANBOD</span>
          </div>
          
          <h2 className="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 mb-6 sm:mb-8 px-2">
            {scarcityData.headline}
          </h2>
          
          {/* Slots indicator */}
          <div className="bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 md:p-8 shadow-xl border border-amber-200 mb-8 sm:mb-10">
            <div className="flex items-center justify-center gap-3 sm:gap-4 mb-4 sm:mb-6">
              <div className="text-5xl sm:text-6xl font-black text-amber-500">{scarcityData.slots}</div>
              <div className="text-left">
                <p className="text-xl sm:text-2xl font-bold text-slate-900">plekken</p>
                <p className="text-sm sm:text-base text-slate-600">{scarcityData.timeframe}</p>
              </div>
            </div>
            
            {/* Progress bar visual */}
            <div className="w-full bg-slate-200 rounded-full h-3 sm:h-4 mb-4 sm:mb-6 overflow-hidden">
              <div className="bg-amber-500 h-full rounded-full w-3/5 relative">
                <div className="absolute inset-0 bg-white/30 animate-pulse" />
              </div>
            </div>
            <p className="text-xs sm:text-sm text-slate-500">3 van 5 plekken deze week al geclaimd</p>
          </div>
          
          {/* Points */}
          <div className="space-y-3 sm:space-y-4 mb-8 sm:mb-10">
            {scarcityData.points.map((point, index) => (
              <div key={index} className="flex items-center justify-center gap-2 sm:gap-3">
                {index === 0 && <Clock className="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 flex-shrink-0" />}
                {index === 1 && <Lock className="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 flex-shrink-0" />}
                {index === 2 && <AlertTriangle className="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 flex-shrink-0" />}
                {index === 3 && <Clock className="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 flex-shrink-0" />}
                <span className="text-sm sm:text-base md:text-lg text-slate-700 font-medium">{point}</span>
              </div>
            ))}
          </div>
          
          {/* Urgency callout */}
          <div className="bg-slate-900 text-white rounded-xl sm:rounded-2xl p-4 sm:p-6">
            <p className="text-sm sm:text-base md:text-lg font-semibold">
              {scarcityData.urgencyText}
            </p>
          </div>
        </div>
      </div>
    </section>
  );
};

export default ScarcitySection;
