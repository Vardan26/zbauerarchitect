import { useEffect, useState, useCallback } from "@wordpress/element";
import { useParams, useNavigate } from "react-router-dom";

import BusyWrapper from "../../components/busy-wrapper";
import { GallerySlider } from "../../components/gallery-slider";
import { Project, ProjectTypeTerm } from "../../types";

import placeholderImage from "../../../../assets/images/placeholder.jpg";

type Props = {
  isLoading: boolean;
  allProjects?: Project[];
  projectTypes?: ProjectTypeTerm[];
};

const ProjectsItem = (props: Props) => {
  const { isLoading, allProjects, projectTypes } = props;
  const { slug } = useParams();
  const navigate = useNavigate();

  const currentProject = allProjects?.find((project) => project.slug === slug);
  const [isImageLoaded, setIsImageLoaded] = useState(false);

  useEffect(() => {
    if (currentProject && !currentProject.meta.hasDetailedPage) {
      navigate("/projects");
      return;
    }
  }, [currentProject]);

  useEffect(() => {
    const largeImage = new Image();
    largeImage.src = currentProject?.featured_image_urls?.thumb || "";

    // When the large image has loaded, update the state
    largeImage.onload = () => {
      setIsImageLoaded(true);
    };
  }, [currentProject?.featured_image_urls]);

  const featuredImage =
    (isImageLoaded
      ? currentProject?.featured_image_urls?.full
      : currentProject?.featured_image_urls?.thumb) || placeholderImage;

  return (
    <BusyWrapper isBusy={isLoading || !currentProject}>
      <div className="single-project">
        <div className="banner">
          <figure className="banner-image">
            <img src={featuredImage} />
          </figure>
          <div className="banner-heading">
            <p className="text-lg">{currentProject?.meta.heading}</p>
            <h2 className="single-project-title">
              {currentProject?.plain_title}
            </h2>
          </div>
        </div>
        <div className="container">
          {currentProject?.meta.gallery?.length ? (
            <GallerySlider imageUrls={currentProject?.meta.gallery} />
          ) : (
            ""
          )}
          <ul className="flex types">
            {projectTypes &&
              [...projectTypes] // Clone to avoid mutating original
                ?.filter(
                  (type) => currentProject?.project_types?.includes(type.id)
                )
                ?.map((projectType) => (
                  <li key={projectType.id} className="link">
                    {projectType.name}
                  </li>
                ))}
            <li className="link capitalize">
              {currentProject?.meta?.status || ""}
            </li>
          </ul>
          <h2 className="single-project-title">
            {currentProject?.plain_title}
          </h2>
          <p className="text-lg">{currentProject?.meta.heading}</p>
          <div className="content">
            <p className="text-md light description">
              {currentProject?.plain_content}
            </p>
            <div className="details flex column">
              <ul className="details-list">
                <li className="details-item content">
                  <span className="text-md">Year:</span>
                  <span className="text-md light">
                    {currentProject?.meta.year}
                  </span>
                </li>
                <li className="details-item content">
                  <span className="text-md">Place:</span>
                  <span className="text-md light">
                    {currentProject?.meta.places}
                  </span>
                </li>
                <li className="details-item content">
                  <span className="text-md">Client:</span>
                  <span className="text-md light">
                    {currentProject?.meta.client || "Private"}
                  </span>
                </li>
              </ul>

              <ul className="details-list">
                <li className="details-item content">
                  <span className="text-md ">Design Team</span>
                  <span></span>
                </li>
                <li className="details-item content">
                  <span className="text-md">Principal Architect:</span>
                  <span className="text-md light">
                    {currentProject?.meta.principal_architect}
                  </span>
                </li>
                <li className="details-item content">
                  <span className="text-md">Architects:</span>
                  <span className="text-md light">
                    {currentProject?.meta.architects}
                  </span>
                </li>
              </ul>
              <ul className="details-list">
                <li className="details-item content">
                  <span className="text-md">Project No:</span>
                  <span className="text-md light">
                    {currentProject?.meta.project_number}
                  </span>
                </li>
              </ul>
            </div>
          </div>
          <span
            onClick={() => navigate("/projects")}
            className="link with-icon"
          >
            see all projects
          </span>

          {currentProject?.meta.wireframe ? (
            <div className="banner-bottom">
              <figure className="image">
                <img src={currentProject?.meta.wireframe} />
              </figure>
            </div>
          ) : (
            ""
          )}
        </div>
      </div>
    </BusyWrapper>
  );
};

export default ProjectsItem;
