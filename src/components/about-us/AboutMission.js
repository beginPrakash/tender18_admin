"use client";
import React from "react";
import AboutMissionData from "./AboutMissionData";

const AboutMission = () => {
  return (
    <>
      <div className="about-mission">
        <div className="container-main">
          <div className="about-mission-flex">
            <div className="abut-mission-info">
              {AboutMissionData.map((missiondata, index) => {
                return (
                  <div className="about-mission-block" key={index}>
                    <i className={missiondata.class}></i>
                    <h4>{missiondata.heading}</h4>
                    <p>{missiondata.desc}</p>
                  </div>
                );
              })}
            </div>

            <div className="about-mission-img">
              <img src="/images/about-mission-img.webp" alt="AboutImg" />
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default AboutMission;
