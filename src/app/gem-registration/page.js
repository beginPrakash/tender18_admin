"use client";
import React from 'react';
import AboutBanner from '@/components/about-us/AboutBanner';
import GemRegistrationInfo from '@/components/gem-registration/GemRegistrationInfo';

const GemRegistration = () => {
  return (
    <>
        <AboutBanner 
            title = 'GEM Registration'
        />
        <GemRegistrationInfo />
    </>
  )
}

export default GemRegistration;