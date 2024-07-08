"use client";
import { useEffect, useState } from "react";
import axios from "axios";
import Link from "next/link";

export default function Payment() {
  const [data, setData] = useState([]);
  const [dataDetails, setDataDetails] = useState([]);
  const [upiData, setUpiData] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "payment.php?endpoint=getPaymentData"
        );
        setData(response.data.data.main);
        setDataDetails(Object.values(response.data.data.details));
        setUpiData(Object.values(response.data.data.upi));
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    };

    fetchData();
  }, []);

  return (
    <>
      <div className="payments-page-main">
        <div className="container-main">
          <div className="tenders-info-services-width">
            <div className="paytem-page-title">
              <h2>{data.main_title}</h2>
              <Link href={data.payment_link ?? ""} target="blank">
                Click Here
              </Link>
            </div>

            <div className="payment-page-flex">
              <div className="payment-page-left">
                <p>{data.title}</p>
                <img src={data.image} alt="QR Code"></img>
              </div>

              <div className="payment-page-right">
                <div className="payment-page-right-block">
                  <div className="payment-page-right-title">
                    <h2>{data.bank_title}</h2>
                  </div>

                  <table className="payment-page-table bank-details-table">
                    <tbody>
                      <tr>
                        <td>Bank Name :-</td>
                        <td>Bank Account Number :-</td>
                        <td>Benificiery Name :-</td>
                        <td>IFSC Code :-</td>
                      </tr>
                      {dataDetails.map((paymentsdata, index) => {
                        return (
                          <tr key={index}>
                            <td>{paymentsdata.bank_name}</td>
                            <td>{paymentsdata.acc_no}</td>
                            <td>{paymentsdata.benf_name}</td>
                            <td>{paymentsdata.ifsc_code}</td>
                          </tr>
                        );
                      })}
                    </tbody>
                  </table>
                </div>

                <div className="payment-page-right-block">
                  <div className="payment-page-right-title">
                    <h2>{data.upi_title}</h2>
                  </div>

                  <table className="payment-page-table">
                    <tbody>
                      {upiData.map((paymentsdata, index) => {
                        return (
                          <tr key={index}>
                            <td>{paymentsdata.title}</td>
                            <td>{paymentsdata.upi_no}</td>
                          </tr>
                        );
                      })}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
