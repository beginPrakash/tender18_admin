"use client";
import React, { useState, useEffect } from "react";
import Link from "next/link";
import axios from "axios";

const Footer = () => {
  const [data, setData] = useState([]);
  const [dataDetails, setDataDetails] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "footer.php?endpoint=getFooterData"
        );
        setData(response.data.data.main);
        setDataDetails(Object.values(response.data.data.quick_links));
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    };
    fetchData();
  }, []);

  return (
    <>
      <footer>
        <div className="footer-main">
          <div className="footer-top">
            <div className="container-main">
              <div className="top-footer-flex">
                <div className="top-footer-block">
                  <h6>TENDERS BY PRODUCT</h6>
                  <ul>
                    <li>
                      <Link href="/tender18">Transmission Line Tenders</Link>
                    </li>
                    <li>
                      <Link href="/tender18">Solar Thermal System tenders</Link>
                    </li>
                    <li>
                      <Link href="/tender18">
                        Traffic Signal Lights Tenders
                      </Link>
                    </li>
                    <li>
                      <Link href="/tender18">
                        Traffic Signal Lights Tenders
                      </Link>
                    </li>
                    <li>
                      <Link href="/tender18">
                        Traffic and Road Signs Tenders
                      </Link>
                    </li>
                    <li>
                      <Link href="/tender18">Railway Signalling Tenders</Link>
                    </li>
                    <li>
                      <Link href="/tender18">Airport Lightning Tenders</Link>
                    </li>
                    <li>
                      <Link href="/tender18">Traffic Signs Tenders</Link>
                    </li>
                  </ul>
                </div>

                <div className="top-footer-block">
                  <ul>
                    <li>
                      <Link href="/tender18">Attack Aircraft Tenders</Link>
                    </li>
                    <li>
                      <Link href="/tender18">Defence Helicopters tenders</Link>
                    </li>
                    <li>
                      <Link href="/tender18">Remote Sensing Tenders</Link>
                    </li>
                    <li>
                      <Link href="/tender18">Satellite Imaginery Tenders</Link>
                    </li>
                    <li>
                      <Link href="/tender18">Gas Billing System Tenders</Link>
                    </li>
                    <li>
                      <Link href="/tender18">
                        Energy billing System Tenders
                      </Link>
                    </li>
                    <li>
                      <Link href="/tender18">Water Billing System Tenders</Link>
                    </li>
                  </ul>
                </div>

                <div className="top-footer-block">
                  <ul>
                    <li>
                      <Link href="/tender18">Renewable Energy</Link>
                    </li>
                    <li>
                      <Link href="/tender18">
                        Fire Fighting Vehicles tenders
                      </Link>
                    </li>
                    <li>
                      <Link href="/tender18">Bio Metric System Tenders</Link>
                    </li>
                    <li>
                      <Link href="/tender18">
                        Fire Protective Clothing Tenders
                      </Link>
                    </li>
                    <li>
                      <Link href="/tender18">Energy Management Tenders</Link>
                    </li>
                    <li>
                      <Link href="/tender18">Satellite Telephones Tenders</Link>
                    </li>
                  </ul>
                </div>

                <div className="top-footer-block">
                  <h6>{data.quick_menu_title}</h6>

                  <ul>
                    {dataDetails.map((quickdata, index) => {
                      return (
                        <li key={index}>
                          <Link href={quickdata.menu_link ?? ""}>
                            {quickdata.menu_title}
                          </Link>
                        </li>
                      );
                    })}
                  </ul>
                </div>

                <div className="top-footer-block">
                  <h6>{data.contact_menu_title}</h6>

                  <ul>
                    <li>
                      <i className="fa-solid fa-address-book"></i>{" "}
                      <span
                        dangerouslySetInnerHTML={{ __html: data.address }}
                      ></span>
                    </li>
                    <li>
                      <i className="fa-solid fa-envelope"></i>{" "}
                      <a href="mailto:sales@tender18.com">{data.first_email}</a>{" "}
                      /{" "}
                      <a href="mailto:wecare@tender18.com">
                        {data.second_email}
                      </a>
                    </li>
                    <li>
                      <a href="tel:+917069661818">
                        <i className="fa-solid fa-phone"></i> {data.contact_no}
                      </a>
                    </li>
                  </ul>

                  <div className="social-lonks">
                    <ul>
                      <li>
                        <Link href={data.facebook_link ?? ""}>
                          <i className="fa-brands fa-facebook-f"></i>
                        </Link>
                      </li>
                      <li>
                        <Link href={data.twitter_link ?? ""}>
                          <i className="fa-brands fa-twitter"></i>
                        </Link>
                      </li>
                      <li>
                        <Link href={data.linked_link ?? ""}>
                          <i className="fa-brands fa-linkedin-in"></i>
                        </Link>
                      </li>
                      <li>
                        <Link href={data.youtube_link ?? ""}>
                          <i className="fa-brands fa-youtube"></i>
                        </Link>
                      </li>
                      <li>
                        <Link href={data.instagram_link ?? ""}>
                          <i className="fa-brands fa-instagram"></i>
                        </Link>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div className="footer-bottom">
            <div className="container-main">
              <div className="">
                <div className="footer-bottom-block text-center">
                  <span>{data.copyright_text}</span>
                  <Link href="/terms-and-conditions">{data.terms_text}</Link>
                </div>

                {/* <div className="footer-bottom-block">
                                <Link href='https://www.greencubes.co.in/' target='blank'>Website By: Green Cubes Solutions</Link>
                            </div> */}
              </div>
            </div>
          </div>
        </div>
      </footer>
    </>
  );
};

export default Footer;
