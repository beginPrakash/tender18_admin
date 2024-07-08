"use client"
import React , {useEffect} from 'react';
import AboutBanner from '@/components/about-us/AboutBanner';
import ContactForm from '@/components/contact-us/ContactForm';

const Contact = () => {
  return (
    <>
        <AboutBanner 
            title = 'Contact'
        />
        <ContactForm />
    </>
  )
}

export default Contact;
