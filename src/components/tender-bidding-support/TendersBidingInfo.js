"use client";
import { useEffect, useState } from "react";
import axios from "axios";

const TendersBidingInfo = () => {
  const [data, setData] = useState([]);
  const [details, setDetails] = useState([]);
  const [last_details, setLastDetails] = useState([]);
  const [whatdata, setWhatData] = useState([]);
  const [whatdetails, setWhatDetails] = useState([]);
  const [whomdata, setWhomData] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "tender-bidding-support/features-section.php?endpoint=getFeaturesData"
        );
        setData(response.data.data.main);
        setDetails(response?.data?.data?.details);
        setLastDetails(response?.data?.data?.last_data);
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        setLoading(false);
      }
    };
    const fetchWhatData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "tender-bidding-support/get-section.php?endpoint=getGetData"
        );
        setWhatData(response.data.data.main);
        setWhatDetails(response?.data?.data?.details)
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        setLoading(false);
      }
    };
    const fetchWhomData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "tender-bidding-support/whom-section.php?endpoint=getWhomData"
        );
        setWhomData(response.data.data.main);
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        setLoading(false);
      }
    };
    fetchData();
    fetchWhatData();
    fetchWhomData();
  }, []);
  return (
    <>
      <div className="tender-biding-info-main">
        <div className="tenders-info-services-width">
          <div className="services-title">
            <h2>{data.below_title}</h2>
          </div>
          <div className="tender-biding-info-flex">
            <div className="tender-biding-block">
              {Object.values(details)?.map((infodata, index) => {
                  return (
                    <div className="tender-bidding-inner-block" key={index}>
                      <i className="fa-regular fa-circle-check"></i>
                      <span>
                        {infodata.title}
                      </span>
                    </div>
                );
              })}
            </div>

            <div className="tender-biding-block">
              <img src={data.below_image} alt="Tender Bidding Support in India" />
            </div>

            <div className="tender-biding-block">
                {Object.values(last_details)?.map((infodata, index) => {
                    return (
                      <div className="tender-bidding-inner-block" key={index}>
                        <i className="fa-regular fa-circle-check"></i>
                        <span>
                          {infodata.title}
                        </span>
                      </div>
                  );
                })}
            </div>
          </div>
        </div>
      </div>

      <div className="who-will-get-info">
        <div className="container-main">
          <div className="tenders-info-services-width">
            <div className="who-will-get-flex">
              <div className="who-will-get-left">
                <h2>{whatdata.title}</h2>
              </div>

              <div className="who-will-get-right">
              <p dangerouslySetInnerHTML={{ __html: whatdata.description }}></p>

                <h6>{whatdata.subtitle}</h6>

                <ul>
                  {Object.values(whatdetails)?.map((infodata, index) => {
                    return (
                    <li key={index}>
                      <i className="fa-solid fa-check"></i>{infodata.title}
                    </li>
                    );
                  })}
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="useful-service">
        <div className="container-main">
          <div className="tenders-info-services-width">
            <div className="useful-flex">
              <div className="useful-left">
                <h2>{whomdata.title}</h2>
              </div>

              <div className="useful-right">
                <p dangerouslySetInnerHTML={{ __html: whomdata.description }}></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default TendersBidingInfo;
