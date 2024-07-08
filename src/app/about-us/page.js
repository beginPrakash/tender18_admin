'use client';
import React , {useEffect} from 'react';
import AboutInfo from '@/components/about-us/AboutInfo';
import AboutBanner from '@/components/about-us/AboutBanner';
import AboutMission from '@/components/about-us/AboutMission';
import HomeTestimonialsClients from '@/components/home/HomeTestimonialsClients';

const About = () => {
  return (
    <>
      <AboutBanner 
        title = 'About'
      />
      <AboutInfo />
      <AboutMission />
      <HomeTestimonialsClients />
    </>
  )
}

export default About;
