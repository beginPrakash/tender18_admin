"use client";
import { useEffect, useState } from "react";
import axios from "axios";

const AboutBanner = (props) => {
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
      <div className="about-banner-main">
        <div className="container-main">
          <div className="abour-banner-img"></div>

          <div className="about-banner-info">
            <div className="container-main">
              <div className="about-banner-content">
                <h1>{data.title}</h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default AboutBanner;
