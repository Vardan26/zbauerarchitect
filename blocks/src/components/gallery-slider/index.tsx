import { useState } from "@wordpress/element";

import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Zoom } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/zoom";

type Image = {
  url: string;
  index: number;
};
export const GallerySlider = ({ imageUrls }: { imageUrls: string[] }) => {
  const [activeImage, setActiveImage] = useState<Image | null>(null);

  const isPrevActive = activeImage?.index !== 0;
  const isNextActive = activeImage?.index !== imageUrls.length - 1;

  const nextImage = isNextActive &&
    activeImage && {
      url: imageUrls[activeImage?.index + 1],
      index: activeImage?.index + 1,
    };

  const prevImage = isPrevActive &&
    activeImage && {
      url: imageUrls[activeImage?.index - 1],
      index: activeImage?.index - 1,
    };

  const onSetActiveImage = ({ url, index }: Image) => {
    return setActiveImage({ url, index });
  };

  const handleBackgroundClick = (e: React.MouseEvent<HTMLDivElement>) => {
    if (e.target === e.currentTarget) {
      setActiveImage(null);
    }
  };

  return (
    <>
      <Swiper
        modules={[Navigation]}
        navigation
        zoom={true}
        spaceBetween={24}
        slidesPerView="auto"
      >
        {imageUrls.map((url, index) => (
          <SwiperSlide key={url} style={{ width: "auto" }}>
            <div className="swiper-zoom-container">
              <img
                onClick={() => onSetActiveImage({ url, index })}
                src={url}
                alt="..."
              />
            </div>
          </SwiperSlide>
        ))}
      </Swiper>
      {activeImage ? (
        <div className="slider-popup " onClick={handleBackgroundClick}>
          <div className="slider-popup-container column">
            <i
              onClick={() => setActiveImage(null)}
              className="fa-solid fa-xmark"
            ></i>
            <img src={activeImage.url} alt="..." />
            <div className="flex justify-between slider-popup-container-arrows">
              <i
                onClick={() => prevImage && setActiveImage(prevImage)}
                className={`${
                  !isPrevActive
                    ? "fas fa-arrow-left disabled"
                    : "fas fa-arrow-left"
                }`}
              ></i>
              <i
                onClick={() => nextImage && setActiveImage(nextImage)}
                className={`${
                  !isNextActive
                    ? "fas fa-arrow-right disabled"
                    : "fas fa-arrow-right"
                }`}
              ></i>
            </div>
          </div>
        </div>
      ) : (
        ""
      )}
    </>
  );
};
