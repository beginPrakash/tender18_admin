"use client";
import { useEffect, useState } from "react";
import axios from "axios";
import AboutMissionData from "./AboutMissionData";

const AboutMission = () => {
  const [data, setData] = useState([]);
  const [details, setDetails] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "about-us/why-section.php?endpoint=getWhyData"
        );
        setData(response.data.data.main);
        setDetails(response.data.data.details);


      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);
  return (
    <>
      <div className="about-mission">
        <div className="container-main">
          <div className="about-mission-flex">
            <div className="abut-mission-info">
              <div className="about-mission-block">
                <h4>{data.title}</h4>
                <p>{data.subtitle}</p>
              </div>
              {Object.values(details)?.map((infodata, index) => {
                    return (
                <div className="about-mission-block" key={index}>
                  <i className="fa-solid fa-bullseye"></i>
                  <h4>{infodata.title}</h4>
                  <p>{infodata.description}</p>
                </div>
                );
              })}
              <div className="about-mission-block">
                <i className="fa-solid fa-eye"></i>
                <h4>Only Creative Solutions</h4>
                <p>We are devided all information into different section on our portal to giving better solution to our valuable clients. We also guide to our client to utilize their money, time and manpower by assisting them with successful techniques.</p>
              </div>
            </div>

            <div className="about-mission-img">
              <img src={data.image} alt="AboutImg" />
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default AboutMission;
