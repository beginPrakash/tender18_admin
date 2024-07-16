"use client";
import { useEffect, useState } from "react";
import axios from "axios";

const TermsAndConditionsData = () => {
  const [data, setData] = useState([]);
  const [details, setDetails] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "terms-and-conditions.php?endpoint=getTermsData"
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
      <div className="terms-page-main">
        <div className="container-main">
          <div className="tenders-info-services-width terms-page-width">
            <div className="register-form-title terms-page-title">
              <h2>{data.title}</h2>
              <p dangerouslySetInnerHTML={{ __html: data.description }}></p>
            </div>

            <div className="terms-page-main-block">
              {Object.values(details)?.map((infodata, index) => {
                return (
                  <div className="terms-page-block" key={index}>
                    <h6>{infodata.title}</h6>
                    <p dangerouslySetInnerHTML={{ __html: infodata.description }}></p>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </div>

    </>
  );
};

export default TermsAndConditionsData;
