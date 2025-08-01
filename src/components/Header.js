"use client";
import { useEffect, useState } from "react";
import { Navbar } from "react-bootstrap";
import axios from "axios";
import Link from "next/link";
import { redirect, usePathname, useRouter, useSearchParams } from "next/navigation";
import { useAppDispatch, useAppSelector } from "@/redux/hook";
import { searchkeyword } from "@/redux/reducer/Search";

const Header = () => {
  const [data, setData] = useState([]);
  const [dataDetails, setDataDetails] = useState([]);
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [isActive, setIsActive] = useState(false);
  const [headerkeywords, setHeaderkeywords] = useState("");
  const [rerender, setRerender] = useState(false);
  // // const [itemId, setItemId] = useState(null);
  const [loading, setLoading] = useState(true);
  const dispatch = useAppDispatch();
  const route = useRouter();
const pathname = usePathname()
  const searchParams = useSearchParams();
  const id = searchParams.get("id") || "";
  const keyword_store =
    useAppSelector((state) => state?.tendersearch?.keyword) || "";

  // const token = localStorage.getItem("token");
  // const user_id = localStorage.getItem("user_id");

  const handleChange = (e) => {
    const { name, value } = e.target;
    console.log("🚀 ~ handleChange ~ value:", value);
    dispatch(searchkeyword(value));
    // setHeaderkeywords(value);
  };

  const handleKeyDown = (event) => {
    if (event.key === 'Enter') {
      handleSubmit_header();
    }
  }

  const handleSubmit_header = async (e) => {
    if (typeof window !== "undefined") {
      let filterData = JSON.parse(localStorage.getItem("filterData"));
      console.log("filterData", filterData);
      // dispatch(searchkeyword(headerkeywords));
      if (filterData) {
        const loggdinfilterarray = {
          user_unique_id: filterData.user_unique_id,
          token: filterData.token,
          ref_no: filterData.ref_no,
          keyword: keyword_store || "",
          state: filterData.state,
          city: filterData.city,
          agency: filterData.agency,
          tender_id: filterData.tender_id,
          due_date: filterData.due_date,
          tender_value: filterData.tender_value,
          department: filterData.department,
          type: filterData.type,
          due_dates_arr: filterData.due_dates_arr,
          tender_value_to: filterData.tender_value_to,
          endpoint: filterData.endpoint,
        };
        localStorage.setItem("filterData", JSON.stringify(loggdinfilterarray));
      } else {
        const loggdinfilterarray = {
          user_unique_id: "",
          token: "",
          ref_no: "",
          keyword: keyword_store || "",
          state: "",
          city: "",
          agency: "",
          tender_id: "",
          due_date: "",
          tender_value: "",
          department: "",
          type: "",
          tender_value_to: "",
          due_dates_arr: null,
        };

        localStorage.setItem("filterData", JSON.stringify(loggdinfilterarray));
        // setHeaderkeywords('');
      }
    }
    if (pathname == "/new-tenders") {
      // history.replace("/new-tenders")
      console.log("rerender");
      // setRerender(prev => !prev); // Toggle the state to force a re-render
      window.location = "/new-tenders";
    } else {
      route.push("/new-tenders");
    }
  };

  const handleToggle = () => {
    setIsActive(!isActive);
  };
  const userLogOut = async () => {
    if (typeof window !== "undefined") {
      const token = localStorage.getItem("token");
      const user_id = localStorage.getItem("user_id");

      if (token && user_id) {
        const user = {
          user_unique_id: user_id,
          token: token,
          endpoint: "logoutData",
        };
        try {
          const response = await axios.post(
            `${process.env.NEXT_PUBLIC_API_BASEURL}` + "logout.php",
            user
          );
          console.log(response);
          if (response.data.status == "success") {
            setIsLoggedIn(false);
            localStorage.removeItem("token");
            localStorage.removeItem("user_id");
            redirect("/");
          } else {
            // window.location.href = '/';
            setIsLoggedIn(false);
            localStorage.removeItem("token");
            localStorage.removeItem("user_id");
          }
        } catch (error) {
          console.error(error);
        }
      } else {
        // window.location.href = '/';
        setIsLoggedIn(false);
        localStorage.removeItem("token");
        localStorage.removeItem("user_id");
      }
    }
  };

  useEffect(() => {
    var keyword = "";
    if (typeof window !== "undefined") {
      let filterData = JSON.parse(localStorage.getItem("filterData"));
      if (filterData) {
        keyword = filterData.keyword;
        setHeaderkeywords(filterData.keyword);
      }
    }
    dispatch(searchkeyword(keyword));

    // if (isActive) {
    //   $("body").addClass("menu-open");
    // } else {
    //   $("body").removeClass("menu-open");
    // }

    // $(document).on("click", ".mob-header a", function () {
    //   if ($(".navbar-collapse").hasClass("show")) {
    //     $("button.ml-auto.navbar-toggler").trigger("click");
    //   }
    // });

    const validateLogin = async () => {
      if (typeof window !== "undefined") {
        const token = localStorage.getItem("token");
        const user_id = localStorage.getItem("user_id");
        if (token && user_id) {
          const user = {
            user_unique_id: user_id,
            token: token,
            endpoint: "validateLoginData",
          };
          try {
            const response = await axios.post(
              `${process.env.NEXT_PUBLIC_API_BASEURL}` + "validate-login.php",
              user
            );
            if (response.data.status == "success") {
              setIsLoggedIn(true);
              // window.location.href = '/';
            } else {
              // window.location.href = '/';
              setIsLoggedIn(false);
            }
          } catch (error) {
            console.error(error);
          }
        } else {
          // window.location.href = '/';
          setIsLoggedIn(false);
        }
      }
    };
    validateLogin();

    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "header.php?endpoint=getHeaderData"
        );
        setData(response.data.data.main);
        setDataDetails(Object.values(response.data.data.menu));
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        setLoading(false);
      }
    };
    // const user_id = localStorage.getItem('user_id');
    // const id = user_id;
    // setItemId(id);

    fetchData();

    window.addEventListener("scroll", isSticky);
    return () => {
      window.removeEventListener("scroll", isSticky);
    };
  }, [isActive]);

  const isSticky = (e) => {
    const header = document.querySelector(".desk-header");
    const scrollTop = window.scrollY;
    scrollTop >= 90
      ? header.classList.add("is-sticky")
      : header.classList.remove("is-sticky");
  };

  // if (loading) {
  //   return <div className="loader">
  //     Loading....
  //   </div>;
  // }
  console.log("pathname", pathname);
  return (
    <>
    <a href={"tel:+" + data.whatsapp_num}><p className="main-header-contact hidden-xs hidden-sm">Sales : +{data.whatsapp_num}</p></a>
      <header>
        <div className="desk-header">
          <div className="header-flex">
            {!["/user/new-tenders", "/user/live-tenders"]?.includes(
              pathname
            ) && (
              <div className="header-left">
                <div className="header-logo padding-30">
                  <Link href="/">
                    <img src={data.desktop_logo} alt="" />
                  </Link>
                </div>
              </div>
            )}
            {["/user/new-tenders", "/user/live-tenders"]?.includes(
              pathname
            ) ? (
              <div className="header-left">
                <div className="header-logo padding-300">
                  <Link href="/">
                    <img src={data.desktop_logo} alt="" />
                  </Link>
                </div>
                <div className="user-key-btn d-flex gap-2">
                  <Link href={`/user/new-tenders?id=${id}`}>
                    Today's New Tenders
                  </Link>
                  <Link href={`/user/live-tenders?id=${id}`}>Live Tenders</Link>
                </div>
              </div>
            ) : (
              <div className="header-right">
                <div className="menu-links">
                  <ul>
                    {dataDetails.map((stasdata, index) => {
                      return (
                        <li key={index}>
                          <Link href={stasdata.menu_link}>
                            {stasdata.menu_title}
                          </Link>
                        </li>
                      );
                    })}
                  </ul>
                </div>

                <div className="header-register">
                  {isLoggedIn ? (
                    <>
                      <div className="menu-register">
                        <a href="javascript:void(0)" onClick={userLogOut}>
                          Logout
                        </a>
                      </div>

                      <div className="menu-register">
                        <Link href="/profile">Profile</Link>
                      </div>
                    </>
                  ) : (
                    <>
                      <div className="menu-register">
                        <Link href="/sign-in">{data.button_text1}</Link>
                      </div>
                      <div className="menu-register">
                        <Link href="/register">{data.button_text}</Link>
                      </div>
                      <div className="menu-register">
                        <Link href="/demo-client">{data.button_text2}</Link>
                      </div>
                    </>
                  )}
                </div>
              </div>
            )}
          </div>
          {!["/user/new-tenders", "/user/live-tenders"]?.includes(
            pathname
          ) && (
            <div className="header-keywords-search">
              <div className="text-center-header mt-2 mb-2">
                <input
                  type="text"
                  id="headersearch"
                  name="keywords"
                  onChange={handleChange}
                  onKeyDown={handleKeyDown}
                  placeholder="Search Your Tender Here"
                  value={keyword_store || ""}
                />
                <input
                  type="submit"
                  value="Search"
                  className="submit"
                  onClick={handleSubmit_header}
                ></input>
              </div>
            </div>
          )}
        </div>

        <div className="mob-header">
          <Navbar collapseOnSelect expand="">
            <div className="header-left">
              <div className="header-logo">
                <Link href="/">
                  <img src={data.desktop_logo} alt="" />
                </Link>
              </div>
            </div>
            <Navbar.Toggle
              aria-controls="responsive-navbar-nav"
              className={`ml-auto ${isActive ? "active" : ""} collapsed`}
              onClick={handleToggle}
            />
            <Navbar.Collapse
              id="responsive-navbar-nav"
              className={!isActive ? "d-none" : "show"}
            >
              {["/user/new-tenders", "/user/live-tenders"]?.includes(
                pathname
              ) && (
                <div className="user-key-btn header-tender-btn">
                  <Link onClick={handleToggle} href={`/user/new-tenders?id=${id}`}>
                    Today's New Tenders
                  </Link>
                  <Link onClick={handleToggle} href={`/user/live-tenders?id=${id}`}>Live Tenders</Link>
                </div>
              )}
              {!["/user/new-tenders", "/user/live-tenders"]?.includes(
                pathname
              ) && (
                <div className="header-right">
                  <div className="menu-links">
                    <ul>
                      {dataDetails.map((stasdata, index) => {
                        return (
                          <li key={index}>
                            <Link
                              href={stasdata.menu_link}
                              onClick={() => setIsActive(false)}
                            >
                              {stasdata.menu_title}
                            </Link>
                          </li>
                        );
                      })}
                    </ul>
                  </div>

                  <div className="header-register">
                    {isLoggedIn ? (
                      <>
                        <div className="menu-register">
                          <a
                            href="javascript:void(0)"
                            onClick={() => {
                              userLogOut();
                              setIsActive(false);
                            }}
                          >
                            Logout
                          </a>
                        </div>

                        <div className="menu-register menu-signup">
                          <Link
                            href="/profile"
                            onClick={() => setIsActive(false)}
                          >
                            Profile
                          </Link>
                        </div>
                      </>
                    ) : (
                      <>
                        <div className="menu-register">
                          <Link
                            onClick={() => setIsActive(false)}
                            href="/sign-in"
                          >
                            {data.button_text1}
                          </Link>
                        </div>
                        <div className="menu-register menu-signup">
                          <Link
                            onClick={() => setIsActive(false)}
                            href="/register"
                          >
                            {data.button_text}
                          </Link>
                        </div>
                      </>
                    )}
                  </div>
                </div>
              )}
            </Navbar.Collapse>
          </Navbar>
          {!["/user/new-tenders", "/user/live-tenders"]?.includes(
              pathname
            ) && <div className="header-keywords-search mob-search">
            <div className="text-center mt-2 mb-2">
              <input
                type="text"
                name="keywords"
                onChange={handleChange}
                onKeyDown={handleKeyDown}
                placeholder="Search Your Tender Here"
                value={keyword_store}
              />
              <input
                type="submit"
                value="Search"
                className="submit"
                onClick={handleSubmit_header}
              ></input>
            </div>
          </div>}
        </div>
      </header>
    </>
  );
};

export default Header;
