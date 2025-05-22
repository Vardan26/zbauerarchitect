import { useEffect, useState } from "@wordpress/element";
import { useProjects } from "../../DataContext";
import BusyWrapper from "../busy-wrapper";

const AboutInfo = () => {
  const { isLoading, allProjects } = useProjects();
  var currentYear = new Date().getFullYear();

  const totalUniquePlaces = new Set(
    allProjects?.flatMap(
      (project) =>
        project.meta.places &&
        project.meta.places
          .split(",")
          .map((place) => place.trim())
          .filter(Boolean)
    )
  );

  const placesCount = totalUniquePlaces?.size;
  const constructionYears = currentYear - 1979;
  const architectureYears = currentYear - 2006;
  const completedProjects = (allProjects && 100 + allProjects?.length) || 0;

  return (
    <BusyWrapper isBusy={isLoading}>
      <div className="about-grid">
        <div className="wp-block-group ">
          <h2 className="title-big">{placesCount}+</h2>
          <p className="text-lg">places in Armenia</p>
        </div>
        <div className="wp-block-group ">
          <div className="flex">
            <h2 className="title-big">{constructionYears}</h2>
            <p className="text-lg">years **</p>
          </div>
          <p className="text-lg">in constructions and engineering</p>
        </div>
        <div className="wp-block-group ">
          <h2 className="title-big">{completedProjects}+</h2>
          <p className="text-lg">completed projects</p>
        </div>
        <div className="wp-block-group ">
          <div className="flex">
            <h2 className="title-big">{architectureYears}</h2>
            <p className="text-lg">years</p>
          </div>
          <p className="text-lg">of architectural design</p>
        </div>
      </div>
    </BusyWrapper>
  );
};

export default AboutInfo;
