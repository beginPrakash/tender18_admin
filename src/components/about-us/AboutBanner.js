"use client";
import React from "react";

const AboutBanner = (props) => {
  return (
    <>
      <div className="about-banner-main">
        <div className="container-main">
          <div className="abour-banner-img"></div>

          <div className="about-banner-info">
            <div className="container-main">
              <div className="about-banner-content">
                <h1>{props.title}</h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default AboutBanner;
