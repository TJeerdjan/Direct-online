import React from 'react';
import Header from '../components/landing/Header';
import Hero from '../components/landing/Hero';
import ProblemSection from '../components/landing/ProblemSection';
import ComparisonSection from '../components/landing/ComparisonSection';
import ValueStackSection from '../components/landing/ValueStackSection';
import WhyPossibleSection from '../components/landing/WhyPossibleSection';
import PersonalApproachSection from '../components/landing/PersonalApproachSection';
import ScarcitySection from '../components/landing/ScarcitySection';
import FinalCTASection from '../components/landing/FinalCTASection';
import Footer from '../components/landing/Footer';

const LandingPage = () => {
  return (
    <div className="min-h-screen bg-white">
      <Header />
      <main className="pt-20">
        <Hero />
        <ProblemSection />
        <ComparisonSection />
        <ValueStackSection />
        <WhyPossibleSection />
        <PersonalApproachSection />
        <ScarcitySection />
        <FinalCTASection />
      </main>
      <Footer />
    </div>
  );
};

export default LandingPage;
