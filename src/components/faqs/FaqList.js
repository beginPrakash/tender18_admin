"use client";
import React, { useEffect, useState } from "react";
import Accordion from "react-bootstrap/Accordion";
import axios from "axios";

const FaqList = () => {
  const [faqsData, setFaqsData] = useState([]);
  const [loading, setLoading] = useState(false);

  const getTenderFaqs = async () => {
    try {
      setLoading(true);
      const response = await axios.get(
        `${process.env.NEXT_PUBLIC_API_BASEURL}` +
          "faqs.php?endpoint=getFAQsData"
      );
      console.log("response.data", response.data);
      if (response.data.status == " success") {
        setFaqsData(response?.data?.data?.details)
        setLoading(false);
      }
    } catch (error) {
      setLoading(false);
    }
  };

  useEffect(() => {
    getTenderFaqs();
  }, []);

  return (
    <>
      {loading ? (
        <div className="loader">
          <img src="/images/Iphone-spinner-2.gif" alt="" />
        </div>
      ) : (
        <div className="faqs-main">
          <div className="container-main">
            <div className="register-form-title">
              <h2>Frequently Asked Questions</h2>
            </div>

            <div className="faqs-blocks-main">
              <Accordion defaultActiveKey="1">
                {Object.values(faqsData)?.map((faqdata, index) => {
                  return (
                    <Accordion.Item eventKey={index} key={index}>
                      <Accordion.Header>{faqdata.title}</Accordion.Header>
                      <Accordion.Body>
                        <p dangerouslySetInnerHTML={{__html:faqdata.description}}></p>
                      </Accordion.Body>
                    </Accordion.Item>
                  );
                })}
              </Accordion>
            </div>
          </div>
        </div>
      )}
    </>
  );
};

export default FaqList;
