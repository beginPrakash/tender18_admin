"use client";
import React, { useEffect, useState } from "react";
// import { useSearchParams } from "next/navigation";
import Link from "next/link";
import axios from "axios";
import { useSearchParams } from "next/navigation";

const AfterLoginNewTenders = () => {
  const [data, setData] = useState([]);
  const [link, setLink] = useState([]);
  const [user, setUser] = useState([]);
  const [loading, setLoading] = useState(false);
  const [formData, setFormData] = useState({
    search: "",
  });
  // const [isLoggedIn, setIsLoggedIn] = useState(false);
  // const [search, setSearch] = useState('');
  // const [searchData, setSearchData] = useState(null);

  const searchParams = useSearchParams();
  const id = searchParams.get("id") || "";

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({ ...prevData, [name]: value }));
  };

  const handleSearch = async () => {
    setLoading(true);
    // const token = localStorage.getItem('token');
    // const user_id = localStorage.getItem('user_id');
    try {
      const user1 = {
        user_unique_id: id,
        endpoint: "getExternalLinkNewTendersData",
        search: `${formData.search}`,
      };
      const response1 = await axios.post(
        `${process.env.NEXT_PUBLIC_API_BASEURL}` +
          "external-link-new-tenders.php",
        user1
      );
      localStorage.setItem("search", formData?.search);
      setData(Object.values(response1.data.data.tenders));
      setLink(Object.values(response1.data.data.links));
    } catch (error) {
      console.error(error);
    } finally {
      setLoading(false);
    }
    // setSearch('');
  };

  const paginationLink = async (pageNo = 1) => {
    setLoading(true);
    if (typeof window !== "undefined") {
      window.scrollTo({ top: 0, left: 0, behavior: "smooth" });
    }
    if (typeof pageNo === "string") {
    } else {
      if (typeof window !== "undefined") {
        const token = localStorage.getItem("token");
        const user_id = localStorage.getItem("user_id");
        try {
          const user1 = {
            user_unique_id: id,
            token: token,
            page_no: pageNo,
            search: formData?.search,
            endpoint: "getExternalLinkNewTendersData",
          };
          const response1 = await axios.post(
            `${process.env.NEXT_PUBLIC_API_BASEURL}` +
              "external-link-new-tenders.php",
            user1
          );
          setData(Object.values(response1.data.data.tenders));
          setLink(Object.values(response1.data.data.links));
        } catch (error) {
          console.error(error);
        } finally {
          setLoading(false);
        }
      }
    }
  };

  useEffect(() => {
    const search = localStorage.getItem("search");
    setFormData({ ...formData, search: search });
    const validateLogin = async () => {
      setLoading(true);

      // const token = localStorage.getItem("token");
      // const user_id = localStorage.getItem("user_id");
      try {
        const search = localStorage.getItem("search")
        const user1 = {
          user_unique_id: id,
          ...(search && {search}),
          endpoint: "getExternalLinkNewTendersData",
          // id: id
        };
        const response1 = await axios.post(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "external-link-new-tenders.php",
          user1
        );
        if (response1.data.status === " success") {
          setUser(response1.data.data);
          setData(Object.values(response1.data.data.tenders));
          setLink(Object.values(response1.data.data.links));
        } else {
          if (typeof window !== "undefined") {
            window.location.href = "/tender18";
          }
        }
      } catch (error) {
        console.error(error);
      } finally {
        setLoading(false);
      }
    };
    validateLogin();
  }, []);

  return (
    <>
      {loading ? (
        <div className="loader">
          <img src="/images/Iphone-spinner-2.gif" alt="" />
        </div>
      ) : (
        <div className="login-user">
          <div className="container-main">
            <div className="login-user-title">
              <h1>
                <span>Today's New</span> TENDERS
              </h1>
              <h6>{user.user_name}</h6>
              <h6>{user.user_email}</h6>
            </div>

            <div className="login-user-search">
              <div className="login-user-input">
                <input
                  type="text"
                  name="search"
                  value={formData?.search}
                  onChange={handleChange}
                  placeholder="Search..."
                />
              </div>
              <div className="login-search">
                <button onClick={handleSearch}>
                  <i className="fa-solid fa-magnifying-glass"></i>
                </button>
              </div>
            </div>
            <div className="tenders-list-main user-tenders-list-main">
              <div className="user-live-tenders-flex">
                {data.map((tendersdata, index) => {
                  return (
                    <div className="user-live-tenders-block mb-4" key={index}>
                      <div className="live-tenders-block-inner">
                        <div className="tenders-top-flex">
                          <div className="tenders-top-left">
                            <h6>
                              T18 Ref No : <span>{tendersdata.ref_no}</span>
                            </h6>
                          </div>

                          <div className="location">
                            <h6>
                              Location : <span>{tendersdata.location}</span>
                            </h6>
                          </div>
                        </div>

                        <div className="tender-work">
                          <h4>
                            <Link
                              href={`/tenders-details/${tendersdata.ref_no}/${id}`}
                              target="_blank"
                              dangerouslySetInnerHTML={{
                                __html: tendersdata.title,
                              }}
                            ></Link>
                            {/* <Link href={`/tender18/tenders-details?id=${tendersdata.ref_no}`} dangerouslySetInnerHTML={{ __html:tendersdata.title}}>
                        </Link> */}
                          </h4>
                        </div>

                        <hr />

                        <div className="tender-bottom-flex">
                          <div className="tenders-top-left">
                            <h6>
                              Agency / Dept : <span>{tendersdata.agency}</span>
                            </h6>
                          </div>
                          <div className="tenders-top-right">
                            <h6>
                              Tender Value :{" "}
                              <span>{tendersdata.tender_value}</span>
                            </h6>
                          </div>
                        </div>

                        <div className="tenders-top-flex">
                          <div className="due-date">
                            <h6>
                              Due Date : <span>{tendersdata.due_date}</span>
                            </h6>
                          </div>
                          <div className="tenders-top-right">
                            <Link
                              href={`/tenders-details/${tendersdata.ref_no}/${id}`}
                              target="_blank"
                            >
                              View Documents
                            </Link>
                            {/* <Link
                              href={`https://api.whatsapp.com/send?phone=917069661818&text=I would like to inquire about Tender Ref No : ${tendersdata.ref_no}`}
                              target="_blank"
                            >
                              <img src="/images/whatsapp-icon.webp" />
                            </Link> */}
                          </div>
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>

              <div className="tenders-pagination">
                <ul>
                  {link.map((linkdata, index) => {
                    return (
                      <li key={index}>
                        <button
                          dangerouslySetInnerHTML={{ __html: linkdata }}
                          onClick={() => paginationLink(linkdata)}
                        ></button>
                      </li>
                    );
                  })}
                </ul>
              </div>
            </div>
          </div>
        </div>
      )}
    </>
  );
};

export default AfterLoginNewTenders;
