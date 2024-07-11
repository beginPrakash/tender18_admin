"use client";
import React from "react";

const ContactForm = () => {
  return (
    <>
      <div className="contact-page-main">
        <div className="container-main">
          <div className="contact-page-flex">
            <div className="contact-page-left">
              <div className="why-us-left-title">
                <h4>Contact info</h4>
              </div>

              <div className="contact-page-left-block">
                <i className="fa-solid fa-location-dot"></i>
                <span>
                  401-402, 4th Floor, Shree Narnarayan Palace, NR. Kothawala
                  Flat, Opp. Dadi Dining Hall, Paldi, Ahmedabad, 380006
                </span>
              </div>

              <div className="contact-page-left-block">
                <i className="fa-solid fa-phone"></i>
                <a href="tel:(+91)-7069661818">(+91)-7069661818</a>
              </div>

              <div className="contact-page-left-block">
                <i className="fa-solid fa-envelope"></i>
                <a href="mailto:wecare@tender18.com">
                  wecare@tender18.com / sales@tender18.com
                </a>
              </div>
            </div>

            <div className="contact-page-right">
              <div className="contact-page-right-inner">
                <div className="contact-page-right-title">
                  <h4>Tell us about yourself</h4>
                  <p>
                    Whether you have questions or you would just like to say
                    hello, contact us.
                  </p>
                </div>

                <form action="">
                  <div className="contact-page-form-flex">
                    <div className="contact-page-block">
                      <label htmlFor="">Name</label>
                      <input type="text" placeholder="Name" required/>
                    </div>

                    <div className="contact-page-block">
                      <label htmlFor="">Email</label>
                      <input type="email" placeholder="Email" required/>
                    </div>

                    <div className="contact-page-block">
                      <label htmlFor="">Mobile</label>
                      <input type="number" placeholder="Mobile" required/>
                    </div>

                    <div className="contact-page-block">
                      <label htmlFor="">Subject</label>
                      <input type="text" placeholder="Subject" required/>
                    </div>

                    <div className="contact-page-block">
                      <textarea
                        name=""
                        id=""
                        cols="0"
                        rows="0"
                        placeholder="Message"
                        required
                      ></textarea>
                    </div>
                  </div>

                  <div className="contact-page-submit">
                    <input type="submit" value="Contact Us" />
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default ContactForm;
