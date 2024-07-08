"use client";
import { useEffect } from "react";
import { useRouter } from "next/navigation";

const ScrollToTop = () => {
  // Extracts pathname property(key) from an object
  const router = useRouter();

  // Access the pathname from the router object
  const pathname = router.pathname;

  // Automatically scrolls to top whenever pathname changes
  useEffect(() => {
    if (typeof window !== "undefined") {
      window.scrollTo(0, 0);
    }
  }, [pathname]);
};

export default ScrollToTop;
