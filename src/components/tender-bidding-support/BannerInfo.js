"use client";
import { useEffect, useState } from "react";
import axios from "axios";

const BannerInfo = () => {
  const [data, setData] = useState([]);
  const [details, setDetails] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "tender-bidding-support/banner-section.php?endpoint=getBannerData"
        );
        setData(response.data.data.main);
        setDetails(response?.data?.data?.details)

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
      <div className="tenders-information-banner">
        <div className="tenders-information-banner-img">
          <img src={data.image} alt="Tender Image" />
        </div>
      </div>

      <div className="tenders-info-services-main">
        <div className="container-main">
          <div className="tenders-info-services-width">
            <div className="tenders-info-services-title services-title text-center">
              <h2>{data.title}</h2>
            </div>
            <div className="tenders-info-services-flex">
              <div className="tenders-info-services-left">
              <p dangerouslySetInnerHTML={{ __html: data.description }}></p>
              </div>

              <div className="tenders-info-services-right">
                <img src={data.image} alt="Tender Information" />
              </div>
            </div>
          </div>
        </div>
      </div>

    </>
  );
};

export default BannerInfo;
