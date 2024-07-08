"use client";
import React, { useEffect, useState, useRef, useCallback } from "react";
import Link from "next/link";
import { DateRangePicker } from "rsuite";
import Button from "react-bootstrap/Button";
import { format } from "date-fns";
import axios from "axios";
import ScrollTop from "../ScrollTop";
import StateData from "@/static-data/StateData";
import { searchkeyword } from "@/redux/reducer/Search";
import { useAppDispatch, useAppSelector } from "@/redux/hook";
import "rsuite/dist/rsuite.min.css";
import { usePathname } from "next/navigation";

const NewTendersList = ({ ...props }) => {
  const path = usePathname();
  const [data, setData] = useState([]);
  const [loginFilterdata, setLoginFilterData] = useState([]);
  const [link, setLink] = useState([]);
  const [filterClient, setFilterClient] = useState([]);
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [show, setShow] = useState(false);
  const [agencyValue, setAgencyValue] = useState("");
  const [agencyOptions, setAgencyOption] = useState([]);
  const [selectAgencyOptions, setSelectAgencyOption] = useState([]);
  const [cityValue, setCityValue] = useState("");
  const [cityOptions, setCityOption] = useState([]);
  const [departmentValue, setDepartmentValue] = useState("");
  const [departmentSelectOption, setDepartmentSelectOption] = useState([]);
  const [tenderTypeValue, setTenderTypeValue] = useState("");
  const [tenderTypeSelectOption, setTenderTypeSelectOption] = useState([]);
  const [selectCityOptions, setSelectCityOption] = useState([]);
  const [selectedValues, setSelectedValues] = useState([]);
  const [pagination, setPagination] = useState(false);
  const [loading, setLoading] = useState(false);
  const [formData, setFormData] = useState({
    ref_no: "",
    keywords: "",
    state: "",
    city: "",
    agency_department: "",
    tender_id: "",
    due_date: "",
    due_dates_arr: null,
    tender_value: "",
    tender_value_to: "",
    tender_department: "",
    tender_type: "",
  });
  // const [filter, setFilter] = useState(['ref_no', 'keywords', 'state', 'city', 'agency_department', 'tender_id', 'due_date', 'tender_value', 'tender_department', 'tender_type']);
  // const [filterCity,setFilterCity] = useState("");
  // const [filterAgency,setFilterAgency] = useState("");
  // const [filterDept,setFilterDept] = useState("");
  // const [filterType,setFilterType] = useState("");
  // const [filterKeyword,setFilterKeyword] = useState("");
  const typeRef = useRef(null);
  const cityRef = useRef(null);
  const agencyRef = useRef(null);
  const departmentref = useRef(null);

  const dispatch = useAppDispatch();
  // const keyword_store = useAppSelector((state) => state.tendersearch.keyword);

  const handleDateChange = (newDates) => {
    if (newDates) {
      const formattedDates = newDates
        .map((date) => format(date, "yyyy-MM-dd"))
        .join(" ~ ");
      // console.log('Selected Dates:', formattedDates);
      setFormData({
        ...formData,
        due_date: formattedDates,
        due_dates_arr: newDates,
      });
    } else {
      setFormData({
        ...formData,
        due_date: "",
        due_dates_arr: null,
      });
    }
  };

  const handleCheckboxChange = (e) => {
    const { name, value, checked } = e.target;
    if (checked) {
      setSelectedValues((prevSelectedValues) => [...prevSelectedValues, value]);
    } else {
      setSelectedValues((prevSelectedValues) =>
        prevSelectedValues.filter((item) => item !== value)
      );
    }
    setFormData((prevFormData) => ({
      ...prevFormData,
      [name]: checked
        ? [...prevFormData[name], value]
        : prevFormData[name].filter((item) => item !== value),
    }));
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    console.log({ value });
    setFormData((prevData) => ({ ...prevData, [name]: value }));
  };

  const handleClose = () => setShow(!show);

  const handleReset = async (e) => {
    dispatch(searchkeyword(""));
    if (typeof window !== "undefined") {
      localStorage.removeItem("filterData");
    }
    setSelectAgencyOption([]);
    setSelectCityOption([]);
    setDepartmentSelectOption([]);
    setTenderTypeSelectOption([]);
    setFormData((prevFormData) => ({
      ref_no: "",
      keywords: "",
      state: "".split(","),
      city: "",
      agency_department: "",
      tender_id: "",
      due_date: "",
      tender_value_to: "",
      tender_value: "",
      tender_department: "",
      tender_type: "",
      due_dates_arr: null,
    }));
    const validateLogin = async () => {
      setLoading(true);
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
            // console.log("login");
            const user1 = {
              user_unique_id: user_id,
              token: token,
              endpoint: "getUserNewTendersData",
            };
            const response1 = await axios.post(
              `${process.env.NEXT_PUBLIC_API_BASEURL}` +
                "new-tenders/user-new-tenders.php",
              user1
            );
            setData(Object.values(response1.data.data.tenders));
            setLink(Object.values(response1.data.data.links));

            const filterLoginData = {
              user_unique_id: user_id,
              token: token,
              endpoint: "getFilterData",
            };
            console.log({ filterLoginData }, "22222");
            const filters_api = await axios.post(
              `${process.env.NEXT_PUBLIC_API_BASEURL}` + "filter-data.php",
              filterLoginData
            );
            if (filters_api.data.status == " success") {
              setFilterClient(filters_api.data.data.filters);
              // var dataArray = filters_api.data.data.filters;
              // if(dataArray.includes("city")){
              //   setFilterCity(filters_api.data.data.filter_city);
              // }
              // if(dataArray.includes("agency_department")){
              //   setFilterAgency(filters_api.data.data.filter_agency);
              // }
              // if(dataArray.includes("tender_department")){
              //   setFilterDept(filters_api.data.data.filter_department);
              // }
              // if(dataArray.includes("tender_type")){
              //   setFilterType(filters_api.data.data.filter_type);
              // }
              // if(dataArray.includes("keywords")){
              //   setFilterKeyword(filters_api.data.data.filter_keywords);
              // }
            }
          } else {
            // console.log("guest");
            setIsLoggedIn(false);
            const response2 = await axios.get(
              `${process.env.NEXT_PUBLIC_API_BASEURL}` +
                "new-tenders/new-tenders.php?endpoint=getNewTendersData"
            );
            setData(Object.values(response2.data.data.tenders));
            setLink(Object.values(response2.data.data.links));
          }
        } catch (error) {
          console.error(error);
        } finally {
          setLoading(false);
        }
      } else {
        setIsLoggedIn(false);
        setLoading(true);
        try {
          const response3 = await axios.get(
            `${process.env.NEXT_PUBLIC_API_BASEURL}` +
              "new-tenders/new-tenders.php?endpoint=getNewTendersData"
          );
          setData(Object.values(response3.data.data.tenders));
          setLink(Object.values(response3.data.data.links));
        } catch (error) {
          console.error(error);
        } finally {
          setLoading(false);
        }
        // console.log("guest");
      }
    };
    validateLogin();
  };

  const handleSubmit = async (e) => {
    dispatch(searchkeyword(formData.keywords));
    localStorage.removeItem("filterData");
    setLoading(true);
    window.scrollTo({ top: 0, left: 0, behavior: "smooth" });
    e.preventDefault();
    setShow(false);
    setPagination(true);

    const token = localStorage.getItem("token");
    const user_id = localStorage.getItem("user_id");

    let inputTypeValue = "";
    if (tenderTypeSelectOption?.length > 0) {
      inputTypeValue = tenderTypeSelectOption?.join(",");
    }
    let inputTypeValue2 = "";
    if (selectCityOptions?.length > 0) {
      inputTypeValue2 = selectCityOptions?.join(",");
    }
    let inputTypeValue3 = "";
    if (selectAgencyOptions?.length > 0) {
      inputTypeValue3 = selectAgencyOptions?.join(",");
    }
    let inputTypeValue4 = "";
    if (departmentSelectOption?.length > 0) {
      inputTypeValue4 = departmentSelectOption?.join(",");
    }

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
        if (response.data.status === "success") {
          setIsLoggedIn(true);

          const loggdinfilterarray = {
            user_unique_id: user_id,
            token: token,
            ref_no: `${formData.ref_no}`,
            keyword: `${formData.keywords}`,
            state: `${formData.state}`,
            city: inputTypeValue2,
            agency: inputTypeValue3,
            tender_id: `${formData.tender_id}`,
            due_date: `${formData.due_date}`,
            due_dates_arr: `${formData.due_dates_arr}`,
            tender_value: `${formData.tender_value}`,
            department: inputTypeValue4,
            type: inputTypeValue,
            tender_value_to: `${formData.tender_value_to}`,
            endpoint: "getFilterUserNewTendersData",
          };

          localStorage.setItem(
            "filterData",
            JSON.stringify(loggdinfilterarray)
          );

          const loggdinFilterData = await axios.post(
            `${process.env.NEXT_PUBLIC_API_BASEURL}` +
              "new-tenders/filter-user-new-tenders.php",
            loggdinfilterarray
          );

          if (loggdinFilterData.data.status === " success") {
            setData(Object.values(loggdinFilterData.data.data.tenders));
            setLink(Object.values(loggdinFilterData.data.data.links));
          } else {
            console.log("Not Successfully");
          }
          console.log(loginFilterdata);
        }
      } catch (error) {
        console.error(error);
      } finally {
        setLoading(false);
      }
    } else {
      setIsLoggedIn(false);
      setLoading(true);
      let inputTypeValue = "";
      if (tenderTypeSelectOption?.length > 0) {
        inputTypeValue = tenderTypeSelectOption?.join(",");
      }
      let inputTypeValue2 = "";
      if (selectCityOptions?.length > 0) {
        inputTypeValue2 = selectCityOptions?.join(",");
      }
      let inputTypeValue3 = "";
      if (selectAgencyOptions?.length > 0) {
        inputTypeValue3 = selectAgencyOptions?.join(",");
      }
      let inputTypeValue4 = "";
      if (departmentSelectOption?.length > 0) {
        inputTypeValue4 = departmentSelectOption?.join(",");
      }

      const filterData = {
        ref_no: `${formData.ref_no}`,
        keyword: `${formData.keywords}`,
        state: `${formData.state}`,
        city: inputTypeValue2,
        agency: inputTypeValue3,
        tender_id: `${formData.tender_id}`,
        due_date: `${formData.due_date}`,
        due_dates_arr: `${formData.due_dates_arr}`,
        tender_value: `${formData.tender_value}`,
        tender_value_to: `${formData.tender_value_to}`,
        department: inputTypeValue4,
        type: inputTypeValue,
        endpoint: "getFilterNewTendersData",
      };

      localStorage.setItem("filterData", JSON.stringify(filterData));
      // console.log(filterData);
      try {
        const filters = await axios.post(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "new-tenders/filter-new-tenders.php",
          filterData
        );

        if (filters.data.status == " success") {
          setData(Object.values(filters.data.data.tenders));
          setLink(Object.values(filters.data.data.links));
        } else {
          console.log("Not Successfully");
        }
        setLoading(false);
      } catch (error) {
        setLoading(false);
        console.log("Not Successfully");
      } finally {
        setLoading(false);
      }
    }

    let filterData_new = JSON.parse(localStorage.getItem("filterData"));
    if (filterData_new) {
      console.log(filterData_new);
      if (filterData_new?.agency?.split(",")?.length > 0 && !!filterData_new?.agency) {
        setSelectAgencyOption(filterData_new?.agency?.split(","));
      }
      if (filterData_new?.city?.split(",")?.length > 0 && !!filterData_new?.city) {
        setSelectCityOption(filterData_new?.city?.split(","));
      }

      if (filterData_new?.type?.split(",")?.length > 0 && !!filterData_new?.type) {
        setTenderTypeSelectOption(filterData_new?.type?.split(","));
      }
      if (filterData_new?.department?.split(",")?.length > 0 && !!filterData_new?.department) {
        setDepartmentSelectOption(filterData_new?.department?.split(","));
      }

      setFormData((prevFormData) => ({
        ref_no: filterData_new.ref_no,
        keywords: filterData_new.keyword,
        state:  filterData_new?.state?.split(","),
        city: filterData_new.city,
        tender_id: filterData_new.tender_id,
        agency_department: filterData_new.agency,
        due_date: filterData_new.due_date,
        tender_value_to: filterData_new.tender_value_to,
        tender_value: filterData_new.tender_value,
        tender_department: filterData_new.department,
        tender_type: filterData_new.type,
        due_dates_arr: formData.due_dates_arr,
      }));
    }
  };

  const handleKeywordsChange = (e) => {
    const selectedValues = e.target.value.split(",");
    setFormData((prevFormData) => ({
      ...prevFormData,
      keywords: selectedValues,
    }));
  };

  const paginationLink = async (pageNo = 1) => {
    setPagination(true);
    setLoading(true);
    window.scrollTo({ top: 0, left: 0, behavior: "smooth" });

    if (typeof pageNo === "string") {
    } else {
      const token = localStorage.getItem("token");
      const user_id = localStorage.getItem("user_id");
      console.log("token && user_id", token && user_id, token, user_id);
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
            // console.log("login");

            let filterData_new = JSON.parse(localStorage.getItem("filterData"));
            console.log("filterData_new", filterData_new);
            if (filterData_new) {
              console.log("filterData_new", !!filterData_new);
              if (filterData_new?.agency?.split(",")?.length > 0 && !!filterData_new?.agency) {
                setSelectAgencyOption(filterData_new?.agency?.split(","));
              }
              if (filterData_new?.city?.split(",")?.length > 0 && !!filterData_new?.city) {
                setSelectCityOption(filterData_new?.city?.split(","));
              }

              if (filterData_new?.type?.split(",")?.length > 0 && !!filterData_new?.type) {
                setTenderTypeSelectOption(filterData_new?.type?.split(","));
              }
              if (filterData_new?.department?.split(",")?.length > 0 && !!filterData_new?.department) {
                setDepartmentSelectOption(
                  filterData_new?.department?.split(",")
                );
              }
              var loggdinfilter = {
                user_unique_id: user_id,
                token: token,
                ref_no: filterData_new.ref_no,
                keyword: filterData_new.keyword,
                state: filterData_new.state,
                city: filterData_new.city,
                agency: filterData_new.agency,
                tender_id: filterData_new.tender_id,
                due_date: filterData_new.due_date,
                due_dates_arr: filterData_new.due_dates_arr,
                tender_value: filterData_new.tender_value,
                tender_value_to: filterData_new.tender_value_to,
                department: filterData_new.department,
                type: filterData_new.type,
                endpoint: "getFilterUserNewTendersData",
                page_no: pageNo,
              };
            } else {
              var loggdinfilter = {
                user_unique_id: user_id,
                token: token,
                endpoint: "getFilterUserNewTendersData",
                page_no: pageNo,
              };
            }

            try {
              const filtersLogin = await axios.post(
                `${process.env.NEXT_PUBLIC_API_BASEURL}` +
                  "new-tenders/filter-user-new-tenders.php",
                loggdinfilter
              );

              if (filtersLogin.data.status == " success") {
                setData(Object.values(filtersLogin.data.data.tenders));
                setLink(Object.values(filtersLogin.data.data.links));
              } else {
                console.log("Not Successfully");
              }
            } catch (error) {
              console.log("Not Successfully");
            }
          } else {
            // console.log("guest");

            let filterData_new = JSON.parse(localStorage.getItem("filterData"));
            if (filterData_new) {
              if (filterData_new?.agency?.split(",")?.length > 0 && !!filterData_new?.agency) {
                setSelectAgencyOption(filterData_new?.agency?.split(","));
              }
              if (filterData_new?.city?.split(",")?.length > 0 && !!filterData_new?.city) {
                setSelectCityOption(filterData_new?.city?.split(","));
              }

              if (filterData_new?.type?.split(",")?.length > 0 && !!filterData_new?.type) {
                setTenderTypeSelectOption(filterData_new?.type?.split(","));
              }
              if (filterData_new?.department?.split(",")?.length > 0 && !!filterData_new?.department) {
                setDepartmentSelectOption(
                  filterData_new?.department?.split(",")
                );
              }
              var filterData = {
                ref_no: filterData_new.ref_no,
                keyword: filterData_new.keyword,
                state: filterData_new.state,
                city: filterData_new.city,
                agency: filterData_new.agency,
                tender_id: filterData_new.tender_id,
                due_date: filterData_new.due_date,
                due_dates_arr: filterData_new.due_dates_arr,
                tender_value: filterData_new.tender_value,
                tender_value_to: filterData_new.tender_value_to,
                department: filterData_new.department,
                type: filterData_new.type,
                endpoint: "getFilterNewTendersData",
                page_no: pageNo,
              };
            } else {
              var filterData = {
                endpoint: "getFilterNewTendersData",
                page_no: pageNo,
              };
            }

            // console.log(filterData);

            try {
              const filters = await axios.post(
                `${process.env.NEXT_PUBLIC_API_BASEURL}` +
                  "new-tenders/filter-new-tenders.php",
                filterData
              );

              if (filters.data.status == " success") {
                setData(Object.values(filters.data.data.tenders));
                setLink(Object.values(filters.data.data.links));
              } else {
                console.log("Not Successfully");
              }
            } catch (error) {
              console.log("Not Successfully");
            } finally {
              setLoading(false);
            }
          }
        } catch (error) {
          console.error(error);
        } finally {
          setLoading(false);
        }
      } else {
        setIsLoggedIn(false);
        // console.log("guest");
        let filterData_new = JSON.parse(localStorage.getItem("filterData"));
        if (filterData_new) {
          if (filterData_new?.agency?.split(",")?.length > 0 && !!filterData_new?.agency) {
            setSelectAgencyOption(filterData_new?.agency?.split(","));
          }
          if (filterData_new?.city?.split(",")?.length > 0 && !!filterData_new?.city) {
            setSelectCityOption(filterData_new?.city?.split(","));
          }

          if (filterData_new?.type?.split(",")?.length > 0 && !!filterData_new?.type) {
            setTenderTypeSelectOption(filterData_new?.type?.split(","));
          }
          if (filterData_new?.department?.split(",")?.length > 0 && !!filterData_new?.department) {
            setDepartmentSelectOption(filterData_new?.department?.split(","));
          }
          var filterData = {
            ref_no: filterData_new.ref_no,
            keyword: filterData_new.keyword,
            state: filterData_new.state,
            city: filterData_new.city,
            agency: filterData_new.agency,
            tender_id: filterData_new.tender_id,
            due_date: filterData_new.due_date,
            due_dates_arr: filterData_new.due_dates_arr,
            tender_value: filterData_new.tender_value,
            tender_value_to: filterData_new.tender_value_to,
            department: filterData_new.department,
            type: filterData_new.type,
            endpoint: "getFilterNewTendersData",
            page_no: pageNo,
          };
        } else {
          var filterData = {
            endpoint: "getFilterNewTendersData",
            page_no: pageNo,
          };
        }

        // console.log(filterData);

        try {
          const filters = await axios.post(
            `${process.env.NEXT_PUBLIC_API_BASEURL}` +
              "new-tenders/filter-new-tenders.php",
            filterData
          );

          if (filters.data.status == " success") {
            setData(Object.values(filters.data.data.tenders));
            setLink(Object.values(filters.data.data.links));
          } else {
            console.log("Not Successfully");
          }
        } catch (error) {
          console.log("Not Successfully");
        } finally {
          setLoading(false);
        }
      }

      let filterData_new = JSON.parse(localStorage.getItem("filterData"));
      if (filterData_new) {
        console.log(filterData_new);
        let date_data = null;
        let data2 = filterData_new?.due_dates_arr?.split(",");
        if (data2) {
          date_data = [new Date(data2[0]), new Date(data2[1])];
        }
        setFormData((prevFormData) => ({
          ref_no: filterData_new.ref_no,
          keywords: filterData_new.keyword,
          state:  filterData_new?.state?.split(","),
          city: filterData_new.city,
          agency_department: filterData_new.agency,
          tender_id: filterData_new.tender_id,
          due_date: filterData_new.due_date,
          due_dates_arr: date_data,
          tender_value_to: filterData_new.tender_value_to,
          tender_value: filterData_new.tender_value,
          tender_department: filterData_new.department,
          tender_type: filterData_new.type,
        }));
      }
    }
  };

  const citySuggestions = useCallback(async () => {
    const suggestionsData = {
      city_like: cityValue,
      endpoint: "getCitySuggestionsData",
    };
    try {
      if (cityValue) {
        const filters_api_city = await axios.post(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` + "city-suggestions.php",
          suggestionsData
        );
        if (filters_api_city.data.status === "success") {
          setCityOption(filters_api_city.data?.cities);
        }
      }
    } catch (error) {
      console.error(error);
    }
  }, [cityValue]);

  useEffect(() => {
    citySuggestions();
  }, [citySuggestions]);

  const agencySuggestions = useCallback(async () => {
    const suggestionsData1 = {
      agency_like: agencyValue,
      endpoint: "getAgencySuggestionsData",
    };
    try {
      if (agencyValue) {
        const filters_api_city = await axios.post(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` + "agency-suggestions.php",
          suggestionsData1
        );
        if (filters_api_city.data.status === "success") {
          setAgencyOption(filters_api_city.data.agencies);
        }
      }
    } catch (error) {
      console.error(error);
    }
  }, [agencyValue]);

  useEffect(() => {
    agencySuggestions();
  }, [agencySuggestions]);

  useEffect(() => {
    var keyword = "";
    let filterData_new = JSON.parse(localStorage.getItem("filterData"));
    console.log({ filterData_new });
    if (filterData_new) {
      keyword = filterData_new.keyword;
      let date_data = null;
      if (
        filterData_new.due_dates_arr !== "null" &&
        filterData_new.due_dates_arr !== null &&
        filterData_new.due_dates_arr !== ""
      ) {
        let data2 = filterData_new.due_dates_arr?.split(",");
        date_data = [new Date(data2?.[0]), new Date(data2?.[1])];
      }
      setFormData((prevFormData) => ({
        ref_no: filterData_new.ref_no,
        keywords: filterData_new.keyword,
        state:  filterData_new?.state?.split(","),
        city: filterData_new.city,
        tender_id: filterData_new.tender_id,
        agency_department: filterData_new.agency,
        due_date: filterData_new.due_date,
        tender_value_to: filterData_new.tender_value_to,
        tender_value: filterData_new.tender_value,
        tender_department: filterData_new.department,
        tender_type: filterData_new.type,
        due_dates_arr: date_data,
      }));
    }
    const validateLogin = async () => {
      const token = localStorage.getItem("token");
      const user_id = localStorage.getItem("user_id");
      var filterLoginData = {
        user_unique_id: user_id,
        token: token,
        endpoint: "getFilterData",
      };
      const filters_api = await axios.post(
        `${process.env.NEXT_PUBLIC_API_BASEURL}` + "filter-data.php",
        filterLoginData
      );
      if (filters_api.data.status == " success") {
        setFilterClient(filters_api.data.data.filters);
      }
    };
    validateLogin();
    paginationLink(1);
    dispatch(searchkeyword(keyword));
  }, []);

  // if (loading) {
  //   return <div className="loader">
  //     Loading....
  //   </div>;
  // }
  return (
    <>
      {loading ? (
        <div className="loader">
          <img src="/images/Iphone-spinner-2.gif" alt="" />
        </div>
      ) : (
        <div className="tenders-flex">
          <>
            {isLoggedIn ? (
              //logged-in
              <>
                <div className="tenders-filters">
                  <>
                    <div className="filters-title d-flex ">
                      <h4>Filters By:</h4>
                    </div>
                    <div className={`tenders-filters-main ${show && "show"}`}>
                      <div className="filters-close" onClick={handleClose}>
                        <span className="filter-close-icon"></span>
                      </div>
                      {filterClient.includes("ref_no") && (
                        <>
                          <div className="tenders-filters-block">
                            <h6>T18 Ref No</h6>
                            <input
                              type="text"
                              placeholder="Enter T18 Ref No..."
                              onChange={handleChange}
                              name="ref_no"
                              value={formData.ref_no}
                            />
                          </div>
                        </>
                      )}
                      {filterClient.includes("keywords") && (
                        <>
                          <div className="tenders-filters-block">
                            <h6>Keyword</h6>
                            <input
                              type="text"
                              name="keywords"
                              onChange={handleChange}
                              placeholder="Enter Keyword"
                              value={formData.keywords}
                            />
                          </div>
                        </>
                      )}
                      {filterClient.includes("state") && (
                        <>
                          <div className="tenders-filters-block">
                            <h6>State</h6>
                            <div className="filters-checkbox">
                              {StateData.map((state, index) => {
                                if (index > 0) {
                                  return (
                                    <label
                                      htmlFor={"DESK_" + index}
                                      key={index}
                                    >
                                      {state.data}
                                      <input
                                        type="checkbox"
                                        name="state"
                                        value={state.data}
                                        id={"DESK_" + index}
                                        onChange={handleCheckboxChange}
                                        checked={
                                          formData.state
                                            ? formData.state.some(
                                                (checkedCheckbox) =>
                                                  checkedCheckbox === state.data
                                              )
                                            : ""
                                        }
                                      />
                                      <span className="checkmark"></span>
                                    </label>
                                  );
                                }
                              })}
                            </div>
                          </div>
                        </>
                      )}
                      {filterClient.includes("city") && (
                        <div className="tenders-filters-block city">
                          <h6>City</h6>
                          <div className="bootstrap-tagsinput">
                            {selectCityOptions?.filter((d) => d)?.length > 0 &&
                              selectCityOptions?.map((d) => {
                                return (
                                  <span
                                    key={d}
                                    className="tag label label-info text-white"
                                  >
                                    {d}
                                    <span
                                      className="p-md-4 cursor-pointer"
                                      onClick={() =>
                                        setSelectCityOption(
                                          selectCityOptions?.filter(
                                            (e) => e !== d
                                          )
                                        )
                                      }
                                    >
                                      X
                                    </span>
                                  </span>
                                );
                              })}
                            <input
                              type="text"
                              name="city"
                              placeholder="Enter City"
                              onChange={(e) => {
                                setCityValue(e?.target?.value);
                              }}
                              ref={cityRef}
                              onKeyDown={(e) => {
                                if (e.key === "Enter") {
                                  setCityValue("");
                                  if (
                                    !selectCityOptions?.some(
                                      (d) => d === e?.target?.value
                                    )
                                  ) {
                                    setSelectCityOption([
                                      ...selectCityOptions,
                                      e?.target?.value,
                                    ]);
                                  }
                                }
                              }}
                              value={cityValue}
                            />
                          </div>
                          {cityValue && (
                            <div className="suggestions">
                              {cityOptions?.map((d, ind) => {
                                return (
                                  <li
                                    key={ind}
                                    onClick={() => {
                                      cityRef.current.focus();
                                      setCityValue("");
                                      if (
                                        !selectCityOptions?.some(
                                          (e) => d === e
                                        )
                                      ) {
                                        setSelectCityOption([
                                          ...selectCityOptions,
                                          d,
                                        ]);
                                      }
                                    }}
                                  >
                                    {d}
                                  </li>
                                );
                              })}
                            </div>
                          )}
                        </div>
                      )}
                      {filterClient.includes("agency_department") && (
                        <div className="tenders-filters-block agency">
                          <h6>Agency</h6>
                          <div className="bootstrap-tagsinput">
                            {selectAgencyOptions?.filter((d) => d)?.length >
                              0 &&
                              selectAgencyOptions?.map((d) => {
                                return (
                                  <span
                                    className="tag label label-info text-white"
                                    key={d}
                                  >
                                    {d}
                                    <span
                                      className="p-md-4 cursor-pointer"
                                      onClick={() =>
                                        setSelectAgencyOption(
                                          selectAgencyOptions?.filter(
                                            (e) => e !== d
                                          )
                                        )
                                      }
                                    >
                                      X
                                    </span>
                                  </span>
                                );
                              })}
                            <input
                              type="text"
                              name="agency_department"
                              data-role="tagsinput1"
                              placeholder="Enter Agency"
                              onChange={(e) => {
                                setAgencyValue(e?.target?.value);
                              }}
                              ref={agencyRef}
                              onKeyDown={(e) => {
                                if (e.key === "Enter") {
                                  setAgencyValue("");
                                  if (
                                    !selectAgencyOptions?.some(
                                      (d) => d === e?.target?.value
                                    )
                                  ) {
                                    setSelectAgencyOption([
                                      ...selectAgencyOptions,
                                      e?.target?.value,
                                    ]);
                                  }
                                }
                              }}
                              value={agencyValue}
                            />
                          </div>
                          {agencyValue && (
                            <div className="suggestions">
                              {agencyOptions?.map((d, ind) => {
                                return (
                                  <li
                                    key={ind}
                                    onClick={() => {
                                      setAgencyValue("");
                                      agencyRef.current.focus();
                                      if (
                                        !selectAgencyOptions?.some(
                                          (e) => d === e
                                        )
                                      ) {
                                        setSelectAgencyOption([
                                          ...selectAgencyOptions,
                                          d,
                                        ]);
                                      }
                                    }}
                                  >
                                    {d}
                                  </li>
                                );
                              })}
                            </div>
                          )}
                        </div>
                      )}
                      {filterClient.includes("tender_id") && (
                        <>
                          <div className="tenders-filters-block">
                            <h6>Tender ID</h6>
                            <input
                              type="text"
                              name="tender_id"
                              placeholder="Enter Tender ID"
                              onChange={handleChange}
                              value={formData.tender_id}
                            />
                          </div>
                        </>
                      )}
                      {filterClient.includes("due_date") && (
                        <>
                          <div className="tenders-filters-block">
                            <h6>Due Date</h6>
                            <DateRangePicker
                              name="due_date"
                              placeholder="Select Date..."
                              onChange={handleDateChange}
                              cleanable
                              placement="auto"
                              value={formData.due_dates_arr}
                            />
                          </div>
                        </>
                      )}
                      {filterClient.includes("tender_value") && (
                        <>
                          <div className="tenders-filters-block">
                            <h6>Tender Value</h6>
                            <div className="Tender-value-flex">
                              <div className="tender-value-block">
                                <input
                                  type="text"
                                  name="tender_value_to"
                                  placeholder="From"
                                  onChange={handleChange}
                                  value={formData.tender_value_to}
                                />
                              </div>
                              <div className="tender-value-block">
                                <input
                                  type="text"
                                  name="tender_value"
                                  placeholder="To"
                                  onChange={handleChange}
                                  value={formData.tender_value}
                                />
                              </div>
                            </div>
                          </div>
                        </>
                      )}
                      {filterClient.includes("tender_department") && (
                        <div className="tenders-filters-block">
                          <h6>Department Type</h6>
                          <div className="bootstrap-tagsinput">
                            {departmentSelectOption?.filter((d) => d)?.length >
                              0 &&
                              departmentSelectOption?.map((d) => {
                                return (
                                  <span
                                    key={d}
                                    className="tag label label-info text-white"
                                  >
                                    {d}
                                    <span
                                      className="p-md-4 cursor-pointer"
                                      onClick={() =>
                                        setDepartmentSelectOption(
                                          departmentSelectOption?.filter(
                                            (e) => e !== d
                                          )
                                        )
                                      }
                                    >
                                      X
                                    </span>
                                  </span>
                                );
                              })}
                            <input
                              type="text"
                              name="tender_department"
                              data-role="tagsinput"
                              placeholder="Enter Department Type"
                              ref={departmentref}
                              onKeyDown={(e) => {
                                if (!!e?.target?.value && e.key === "Enter") {
                                  setDepartmentSelectOption([
                                    ...departmentSelectOption,
                                    e?.target?.value,
                                  ]);
                                  setDepartmentValue("");
                                }
                                if (
                                  !e?.target?.value &&
                                  departmentSelectOption?.length > 0 &&
                                  e?.key === "Backspace"
                                ) {
                                  setDepartmentSelectOption(
                                    departmentSelectOption?.filter(
                                      (data, index) => {
                                        return (
                                          index !==
                                          departmentSelectOption?.length - 1
                                        );
                                      }
                                    )
                                  );
                                }
                              }}
                              onBlur={(e) => {
                                if (!!e?.target?.value) {
                                  setDepartmentSelectOption([
                                    ...departmentSelectOption,
                                    e?.target?.value,
                                  ]);
                                  setDepartmentValue("");
                                }
                              }}
                              onChange={(e) =>
                                setDepartmentValue(e?.target?.value)
                              }
                              value={departmentValue}
                            />
                          </div>
                        </div>
                      )}
                      {filterClient.includes("tender_type") && (
                        <div className="tenders-filters-block">
                          <h6>Tender Type</h6>
                          <div className="bootstrap-tagsinput">
                            {tenderTypeSelectOption?.filter((d) => d)?.length >
                              0 &&
                              tenderTypeSelectOption?.map((d) => {
                                return (
                                  <span className="tag label label-info text-white">
                                    {d}
                                    <span
                                      className="p-md-4 cursor-pointer"
                                      onClick={() =>
                                        setTenderTypeSelectOption(
                                          tenderTypeSelectOption?.filter(
                                            (e) => e !== d
                                          )
                                        )
                                      }
                                    >
                                      X
                                    </span>
                                  </span>
                                );
                              })}
                            <input
                              type="text"
                              name="tender_type"
                              id="tagsInput"
                              ref={typeRef}
                              data-role="tagsinput"
                              placeholder="Enter Tender Type"
                              onKeyDown={(e) => {
                                if (!!e?.target?.value && e.key === "Enter") {
                                  setTenderTypeSelectOption([
                                    ...tenderTypeSelectOption,
                                    e?.target?.value,
                                  ]);
                                  setTenderTypeValue("");
                                }
                                if (
                                  !e?.target?.value &&
                                  tenderTypeSelectOption?.length > 0 &&
                                  e?.key === "Backspace"
                                ) {
                                  setTenderTypeSelectOption(
                                    tenderTypeSelectOption?.filter(
                                      (data, index) => {
                                        return (
                                          index !==
                                          tenderTypeSelectOption?.length - 1
                                        );
                                      }
                                    )
                                  );
                                }
                              }}
                              onBlur={(e) => {
                                if (!!e?.target?.value) {
                                  setTenderTypeSelectOption([
                                    ...tenderTypeSelectOption,
                                    e?.target?.value,
                                  ]);
                                  setTenderTypeValue("");
                                }
                              }}
                              onChange={(e) =>
                                setTenderTypeValue(e?.target?.value)
                              }
                              value={tenderTypeValue}
                            />
                          </div>
                        </div>
                      )}

                      <div className="row filters-submit">
                        <div className="col">
                          <input
                            className=""
                            type="submit"
                            value="Submit"
                            onClick={handleSubmit}
                          ></input>
                        </div>
                        <div className="col">
                          <a className="reset-btn" onClick={handleReset}>
                            Reset
                          </a>
                        </div>
                      </div>
                    </div>
                  </>
                </div>
                <div className="mobile-filters">
                  <Button variant="primary" onClick={handleClose}>
                    Filters
                  </Button>
                </div>
              </>
            ) : (
              //guest
              <>
                <div className="tenders-filters">
                  <div className="filters-title d-flex ">
                    <h4>Filters By:</h4>
                  </div>
                  <div className={`tenders-filters-main ${show && "show"}`}>
                    <div className="filters-close" onClick={handleClose}>
                      <span className="filter-close-icon"></span>
                    </div>
                    <div className="tenders-filters-block">
                      <h6>T18 Ref No</h6>
                      <input
                        type="text"
                        placeholder="Enter T18 Ref No..."
                        onChange={handleChange}
                        name="ref_no"
                        value={formData.ref_no}
                      />
                    </div>
                    <div className="tenders-filters-block">
                      <h6>Keyword</h6>
                      <input
                        type="text"
                        name="keywords"
                        placeholder="Enter Keyword"
                        onChange={handleChange}
                        value={formData.keywords}
                      />
                    </div>
                    <div className="tenders-filters-block">
                      <h6>State</h6>
                      <div className="filters-checkbox">
                        {StateData?.map((state, index) => {
                          if (index > 0) {
                            return (
                              <label htmlFor={"DESK_" + index} key={index}>
                                {state.data}
                                <input
                                  type="checkbox"
                                  name="state"
                                  value={state.data}
                                  id={"DESK_" + index}
                                  onChange={handleCheckboxChange}
                                  checked={
                                    formData.state
                                      ? formData.state.some(
                                          (checkedCheckbox) =>
                                            checkedCheckbox === state.data
                                        )
                                      : ""
                                  }
                                />
                                <span className="checkmark"></span>
                              </label>
                            );
                          }
                        })}
                      </div>
                    </div>
                    <div className="tenders-filters-block city">
                      <h6>City</h6>
                      <div className="bootstrap-tagsinput">
                        {selectCityOptions?.filter((d) => d)?.length > 0 &&
                          selectCityOptions?.map((d) => {
                            return (
                              <span
                                key={d}
                                className="tag label label-info text-white"
                              >
                                {d}
                                <span
                                  className="p-md-4 cursor-pointer"
                                  onClick={() =>
                                    setSelectCityOption(
                                      selectCityOptions?.filter((e) => e !== d)
                                    )
                                  }
                                >
                                  X
                                </span>
                              </span>
                            );
                          })}
                        <input
                          type="text"
                          name="city"
                          placeholder="Enter City"
                          onChange={(e) => {
                            setCityValue(e?.target?.value);
                          }}
                          ref={cityRef}
                          onKeyDown={(e) => {
                            if (e.key === "Enter") {
                              setCityValue("");
                              if (
                                !selectCityOptions?.some(
                                  (d) => d === e?.target?.value
                                )
                              ) {
                                setSelectCityOption([
                                  ...selectCityOptions,
                                  e?.target?.value,
                                ]);
                              }
                            }
                          }}
                          value={cityValue}
                        />
                      </div>
                      {cityValue && (
                        <div className="suggestions">
                          {cityOptions?.map((d, ind) => {
                            return (
                              <li
                                key={ind}
                                onClick={() => {
                                  setCityValue("");
                                  cityRef.current.focus();
                                  if (
                                    !selectCityOptions?.some(
                                      (e) => d === e
                                    )
                                  ) {
                                    setSelectCityOption([
                                      ...selectCityOptions,
                                      d,
                                    ]);
                                  }
                                }}
                              >
                                {d}
                              </li>
                            );
                          })}
                        </div>
                      )}
                    </div>
                    <div className="tenders-filters-block agency">
                      <h6>Agency</h6>
                      <div className="bootstrap-tagsinput">
                        {selectAgencyOptions?.filter((d) => d)?.length > 0 &&
                          selectAgencyOptions?.map((d) => {
                            return (
                              <span
                                className="tag label label-info text-white"
                                key={d}
                              >
                                {d}
                                <span
                                  className="p-md-4 cursor-pointer"
                                  onClick={() =>
                                    setSelectAgencyOption(
                                      selectAgencyOptions?.filter(
                                        (e) => e !== d
                                      )
                                    )
                                  }
                                >
                                  X
                                </span>
                              </span>
                            );
                          })}
                        <input
                          type="text"
                          name="agency_department"
                          data-role="tagsinput1"
                          placeholder="Enter Agency"
                          onChange={(e) => {
                            setAgencyValue(e?.target?.value);
                          }}
                          ref={agencyRef}
                          onKeyDown={(e) => {
                            if (e.key === "Enter") {
                              setAgencyValue("");
                              if (
                                !selectAgencyOptions?.some(
                                  (d) => d === e?.target?.value
                                )
                              ) {
                                setSelectAgencyOption([
                                  ...selectAgencyOptions,
                                  e?.target?.value,
                                ]);
                              }
                            }
                          }}
                          value={agencyValue}
                        />
                      </div>
                      {agencyValue && (
                        <div className="suggestions">
                          {agencyOptions?.map((d, ind) => {
                            return (
                              <li
                                key={ind}
                                onClick={() => {
                                  setAgencyValue("");
                                  agencyRef.current.focus();
                                  if (
                                    !selectAgencyOptions?.some(
                                      (e) => d === e
                                    )
                                  ) {
                                    setSelectAgencyOption([
                                      ...selectAgencyOptions,
                                      d,
                                    ]);
                                  }
                                }}
                              >
                                {d}
                              </li>
                            );
                          })}
                        </div>
                      )}
                    </div>
                    <div className="tenders-filters-block">
                      <h6>Tender ID</h6>
                      <input
                        type="text"
                        name="tender_id"
                        placeholder="Enter Tender ID"
                        onChange={handleChange}
                        value={formData.tender_id}
                      />
                    </div>
                    <div className="tenders-filters-block">
                      <h6>Due Date</h6>
                      <DateRangePicker
                        name="due_date"
                        placeholder="Select Date..."
                        onChange={handleDateChange}
                        cleanable
                        placement="auto"
                        value={formData.due_dates_arr}
                      />
                    </div>
                    <div className="tenders-filters-block">
                      <h6>Tender Value</h6>
                      <div className="Tender-value-flex">
                        <div className="tender-value-block">
                          <input
                            type="text"
                            name="tender_value_to"
                            placeholder="From"
                            onChange={handleChange}
                            value={formData.tender_value_to}
                          />
                        </div>
                        <div className="tender-value-block">
                          <input
                            type="text"
                            name="tender_value"
                            placeholder="To"
                            onChange={handleChange}
                            value={formData.tender_value}
                          />
                        </div>
                      </div>
                    </div>
                    <div className="tenders-filters-block">
                      <h6>Department Type</h6>
                      <div className="bootstrap-tagsinput">
                        {departmentSelectOption?.filter((d) => d)?.length > 0 &&
                          departmentSelectOption?.map((d) => {
                            return (
                              <span
                                key={d}
                                className="tag label label-info text-white"
                              >
                                {d}
                                <span
                                  className="p-md-4 cursor-pointer"
                                  onClick={() =>
                                    setDepartmentSelectOption(
                                      departmentSelectOption?.filter(
                                        (e) => e !== d
                                      )
                                    )
                                  }
                                >
                                  X
                                </span>
                              </span>
                            );
                          })}
                        <input
                          type="text"
                          name="tender_department"
                          data-role="tagsinput"
                          placeholder="Enter Department Type"
                          ref={departmentref}
                          onKeyDown={(e) => {
                            if (!!e?.target?.value && e.key === "Enter") {
                              setDepartmentSelectOption([
                                ...departmentSelectOption,
                                e?.target?.value,
                              ]);
                              setDepartmentValue("");
                            }
                            if (
                              !e?.target?.value &&
                              departmentSelectOption?.length > 0 &&
                              e?.key === "Backspace"
                            ) {
                              setDepartmentSelectOption(
                                departmentSelectOption?.filter(
                                  (data, index) => {
                                    return (
                                      index !==
                                      departmentSelectOption?.length - 1
                                    );
                                  }
                                )
                              );
                            }
                          }}
                          onBlur={(e) => {
                            if (!!e?.target?.value) {
                              setDepartmentSelectOption([
                                ...departmentSelectOption,
                                e?.target?.value,
                              ]);
                              setDepartmentValue("");
                            }
                          }}
                          onChange={(e) => setDepartmentValue(e?.target?.value)}
                          value={departmentValue}
                        />
                      </div>
                    </div>
                    <div className="tenders-filters-block">
                      <h6>Tender Type</h6>
                      <div className="bootstrap-tagsinput">
                        {tenderTypeSelectOption?.filter((d) => d)?.length > 0 &&
                          tenderTypeSelectOption?.map((d) => {
                            return (
                              <span className="tag label label-info text-white">
                                {d}
                                <span
                                  className="p-md-4 cursor-pointer"
                                  onClick={() =>
                                    setTenderTypeSelectOption(
                                      tenderTypeSelectOption?.filter(
                                        (e) => e !== d
                                      )
                                    )
                                  }
                                >
                                  X
                                </span>
                              </span>
                            );
                          })}
                        <input
                          type="text"
                          name="tender_type"
                          id="tagsInput"
                          ref={typeRef}
                          data-role="tagsinput"
                          placeholder="Enter Tender Type"
                          onKeyDown={(e) => {
                            if (!!e?.target?.value && e.key === "Enter") {
                              setTenderTypeSelectOption([
                                ...tenderTypeSelectOption,
                                e?.target?.value,
                              ]);
                              setTenderTypeValue("");
                            }
                            if (
                              !e?.target?.value &&
                              tenderTypeSelectOption?.length > 0 &&
                              e?.key === "Backspace"
                            ) {
                              setTenderTypeSelectOption(
                                tenderTypeSelectOption?.filter(
                                  (data, index) => {
                                    return (
                                      index !==
                                      tenderTypeSelectOption?.length - 1
                                    );
                                  }
                                )
                              );
                            }
                          }}
                          onBlur={(e) => {
                            if (!!e?.target?.value) {
                              setTenderTypeSelectOption([
                                ...tenderTypeSelectOption,
                                e?.target?.value,
                              ]);
                              setTenderTypeValue("");
                            }
                          }}
                          onChange={(e) => setTenderTypeValue(e?.target?.value)}
                          value={tenderTypeValue}
                        />
                      </div>
                    </div>
                    <div className="row filters-submit">
                      <div className="col">
                        <input
                          className=""
                          type="submit"
                          value="Submit"
                          onClick={handleSubmit}
                        ></input>
                      </div>
                      <div className="col">
                        <a className="reset-btn" onClick={handleReset}>
                          Reset
                        </a>
                      </div>
                    </div>
                  </div>
                </div>

                <div className="mobile-filters">
                  <Button variant="primary" onClick={handleClose}>
                    Filters
                  </Button>
                </div>
              </>
            )}
          </>
          <ScrollTop />
          <div className="tenders-list-main">
            <div className="tenders-pages-link">
              <ul>
                <li>
                  <Link
                    className={"/new-tenders" === path ? "active" : "none"}
                    href="/new-tenders"
                  >
                    New Tenders
                  </Link>
                </li>
                <li>
                  <Link
                    className={"/live-tenders" === path ? "active" : "none"}
                    href="/live-tenders"
                  >
                    Live Tenders
                  </Link>
                </li>
                <li>
                  <Link
                    className={"/archive-tenders" === path ? "active" : "none"}
                    href="/archive-tenders"
                  >
                    Archive Tenders
                  </Link>
                </li>
              </ul>
            </div>
            <div className="live-tenders-flex">
              {data.length == 0 ? <p>No data found.</p> : ""}
              {data.map((tendersdata, index) => {
                return (
                  <div className="live-tenders-block" key={index}>
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
                            href={`/tenders-details/${tendersdata.ref_no}`}
                            dangerouslySetInnerHTML={{
                              __html: tendersdata.title,
                            }}
                            target="_blank"
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
                            href={`/tenders-details/${tendersdata.ref_no}`}
                            target="_blank"
                          >
                            View Documents
                          </Link>
                          {!isLoggedIn ? (
                            <>
                              <Link
                                href={`https://api.whatsapp.com/send?phone=917069661818&text=I would like to inquire about Tender Ref No : ${tendersdata.ref_no}`}
                                target="_blank"
                              >
                                <img src={"/images/whatsapp-icon.webp"} alt="Tender18 Infotech" />
                              </Link>
                            </>
                          ) : (
                            <></>
                          )}
                        </div>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>

            <div className="tenders-pagination">
              {/* <Pagination /> */}
              <ul>
                {link.map((linkData, index) => {
                  return (
                    <li key={index}>
                      <button
                        dangerouslySetInnerHTML={{ __html: linkData }}
                        onClick={() => paginationLink(linkData)}
                      ></button>
                    </li>
                  );
                })}
              </ul>
            </div>
          </div>
        </div>
      )}
    </>
  );
};

export default NewTendersList;
