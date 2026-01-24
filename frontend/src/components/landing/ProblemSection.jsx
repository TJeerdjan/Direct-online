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
    <section className="py-24 bg-slate-900 text-white relative overflow-hidden">
      {/* Background accent */}
      <div className="absolute top-0 right-0 w-96 h-96 bg-red-500/5 rounded-full blur-3xl" />
      
      <div className="container mx-auto px-6 lg:px-12 relative z-10">
        <div className="text-center mb-16">
          <h2 className="text-3xl md:text-4xl font-bold mb-4">
            {problemData.headline}
          </h2>
          <div className="w-24 h-1 bg-red-500 mx-auto rounded-full" />
        </div>
        
        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          {problemData.problems.map((problem, index) => {
            const IconComponent = iconMap[problem.icon];
            return (
              <div 
                key={index} 
                className="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-2xl p-6 transition-all duration-300 hover:border-red-500/50 hover:bg-slate-800/80 group"
              >
                <div className="w-14 h-14 bg-red-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-red-500/20 transition-colors">
                  <IconComponent className="w-7 h-7 text-red-400" />
                </div>
                <h3 className="text-xl font-semibold mb-2 text-white">
                  {problem.title}
                </h3>
                <p className="text-slate-400">
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
