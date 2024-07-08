"use client";
import React from "react";

const TendersBanner = (props) => {
  return (
    <>
      <div className="tenders-banner">
        <div className="tenders-banner-main">
          <div className="tenders-banner-img">
            <img src={props.imgsrc} alt={props.alt} />
          </div>

          <div className="tenders-banner-info">
            <h1>{props.heading}</h1>
          </div>
        </div>
      </div>
    </>
  );
};

export default TendersBanner;
