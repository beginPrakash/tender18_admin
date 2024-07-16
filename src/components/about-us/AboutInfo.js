"use client";
import { useEffect, useState } from "react";
import axios from "axios";

const AboutInfo = () => {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "about-us/banner-section.php?endpoint=getBannerData"
        );
        setData(response.data.data.main);

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
      <div className="about-info">
        <div className="container-main">
          <div className="about-infp-flex">
            <div className="about-info-img">
              <img src={data.image} alt="AboutImg" />
            </div>

            <div className="about-nfo-content home-about-right">
              <h2>{data.title}</h2>
              <p dangerouslySetInnerHTML={{ __html: data.description }}></p>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default AboutInfo;
