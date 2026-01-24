import React from 'react';
import { Clock, Euro, FileX, MessageSquareX } from 'lucide-react';
import { problemData } from '../../data/mock';

const iconMap = {
  Clock,
  Euro,
  FileX,
  MessageSquareX
};

const ProblemSection = () => {
  return (
    <section className="py-12 sm:py-16 md:py-24 bg-slate-900 text-white relative overflow-hidden">
      {/* Background accent */}
      <div className="absolute top-0 right-0 w-64 sm:w-96 h-64 sm:h-96 bg-red-500/5 rounded-full blur-3xl" />
      
      <div className="container mx-auto px-4 sm:px-6 lg:px-12 relative z-10">
        <div className="text-center mb-10 sm:mb-16">
          <h2 className="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">
            {problemData.headline}
          </h2>
          <div className="w-16 sm:w-24 h-1 bg-red-500 mx-auto rounded-full" />
        </div>
        
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 md:gap-8">
          {problemData.problems.map((problem, index) => {
            const IconComponent = iconMap[problem.icon];
            return (
              <div 
                key={index} 
                className="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-xl sm:rounded-2xl p-5 sm:p-6 transition-all duration-300 hover:border-red-500/50 hover:bg-slate-800/80 group"
              >
                <div className="w-12 h-12 sm:w-14 sm:h-14 bg-red-500/10 rounded-lg sm:rounded-xl flex items-center justify-center mb-3 sm:mb-4 group-hover:bg-red-500/20 transition-colors">
                  <IconComponent className="w-6 h-6 sm:w-7 sm:h-7 text-red-400" />
                </div>
                <h3 className="text-lg sm:text-xl font-semibold mb-2 text-white">
                  {problem.title}
                </h3>
                <p className="text-sm sm:text-base text-slate-400">
                  {problem.description}
                </p>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
};

export default ProblemSection;
