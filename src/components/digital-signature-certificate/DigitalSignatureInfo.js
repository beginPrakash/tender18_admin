"use client";
import { useEffect, useState } from "react";
import axios from "axios";
import DigitalSignatureInfoData from "./DigitalSignatureInfoData";

const DigitalSignatureInfo = () => {
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
            "digital-signature-certificate/feature-section.php?endpoint=getFeatureData"
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
            "digital-signature-certificate/what-section.php?endpoint=getWhatData"
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
            "digital-signature-certificate/whom-section.php?endpoint=getWhomData"
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
      <div className="features-tender-info-main">
        <div className="features-tender-info">
          <img src="/images/tender-information-3.webp" alt="Digital Signature Certificate" />

          <div className="features-lists">
            <div className="tenders-info-services-width">
              <div className="services-title">
                <h2>{data.title}</h2>
              </div>

              <div className="features-list-flex">
                {Object.values(details)?.map((infodata, index) => {
                  return (
                    <div className="features-list-block" key={index}>
                      <div className="features-list-block-inner">
                        <div className="features-list-img">
                          <img src={infodata.image} alt="Digital Signature" />
                        </div>
                        <div className="features-list-desc">
                          <p>{infodata.title}</p>
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>
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

export default DigitalSignatureInfo;
