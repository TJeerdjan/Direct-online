import React from 'react';
import { Users, CheckCircle2 } from 'lucide-react';
import { personalApproachData } from '../../data/mock';

const PersonalApproachSection = () => {
  return (
    <section className="py-24 bg-slate-900 text-white relative overflow-hidden">
      {/* Background accents */}
      <div className="absolute top-0 left-1/4 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl" />
      <div className="absolute bottom-0 right-1/4 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl" />
      
      <div className="container mx-auto px-6 lg:px-12 relative z-10">
        <div className="max-w-4xl mx-auto">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            {/* Content */}
            <div>
              <div className="inline-flex items-center gap-2 bg-teal-500/20 text-teal-300 px-4 py-2 rounded-full mb-6">
                <Users className="w-4 h-4" />
                <span className="text-sm font-semibold">Persoonlijke aanpak</span>
              </div>
              
              <h2 className="text-3xl md:text-4xl font-bold mb-4">
                {personalApproachData.headline}
              </h2>
              <p className="text-xl text-slate-300 mb-6">
                {personalApproachData.subheadline}
              </p>
              <p className="text-slate-400 leading-relaxed mb-8">
                {personalApproachData.description}
              </p>
              
              <div className="space-y-4">
                {personalApproachData.benefits.map((benefit, index) => (
                  <div key={index} className="flex items-center gap-3">
                    <CheckCircle2 className="w-6 h-6 text-teal-400 flex-shrink-0" />
                    <span className="text-lg text-white">{benefit}</span>
                  </div>
                ))}
              </div>
            </div>
            
            {/* Visual element */}
            <div className="relative">
              <div className="bg-slate-800 rounded-3xl p-8 border border-slate-700">
                <div className="aspect-square bg-slate-700/50 rounded-2xl flex items-center justify-center relative overflow-hidden">
                  {/* Office illustration */}
                  <div className="text-center">
                    <div className="w-32 h-32 bg-teal-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                      <Users className="w-16 h-16 text-teal-400" />
                    </div>
                    <p className="text-xl font-semibold text-white">Face-to-face</p>
                    <p className="text-slate-400">bij jou op kantoor</p>
                  </div>
                  
                  {/* Decorative elements */}
                  <div className="absolute top-4 right-4 w-12 h-12 bg-amber-500/20 rounded-full blur-xl" />
                  <div className="absolute bottom-4 left-4 w-16 h-16 bg-teal-500/20 rounded-full blur-xl" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default PersonalApproachSection;
