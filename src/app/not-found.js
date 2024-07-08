"use client";
import React, { useEffect } from "react";
import Link from "next/link";

const NotFound = () => {
  return (
    <>
      <div id="notfound">
        <div className="notfound">
          <h3>Oops! Page not found</h3>
          <h1>404</h1>
          <h3 className="not-desc">
            WE ARE SORRY, BUT THE PAGE YOU REQUESTED WAS NOT FOUND
          </h3>
          <Link href="/tender18">Back To Home</Link>
        </div>
      </div>
    </>
  );
};

export default NotFound;
