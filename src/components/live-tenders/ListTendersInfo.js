"use client";
import { useEffect, useState } from "react";
import axios from "axios";

const ListTendersInfo = () => {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "tender-information-service/provide-section.php?endpoint=getProvideData"
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
      <div className="list-tender-info">
        <div className="container-main">
          <div className="tenders-info-services-width">
            <div className="tenders-info-services-flex lit-tender-info-flex">
              <div className="tenders-info-services-left">
                <h4>{data.title}</h4>
                <p>
                <p dangerouslySetInnerHTML={{ __html: data.description }}></p>
                </p>
              </div>

              <div className="tenders-info-services-right">
                <img
                  src={data.image}
                  alt="Tender Information in India"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default ListTendersInfo;
