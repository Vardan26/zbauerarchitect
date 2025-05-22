import { useEffect, useState, useCallback } from "@wordpress/element";
import { useNavigate, useLocation } from "react-router-dom";

import BusyWrapper from "../../components/busy-wrapper";
import { Project, ProjectTypeTerm } from "../../types";

import placeholderImage from "../../../../assets/images/placeholder.jpg";

type Props = {
  isLoading: boolean;
  allProjects?: Project[];
  projectTypes?: ProjectTypeTerm[];
};

const ProjectsPage = (props: Props) => {
  const { isLoading, allProjects, projectTypes } = props;

  const [filteredProjects, setFilteredProjects] = useState(allProjects);
  const [isFilterOpen, setFilterOpen] = useState(false);
  const [showScrollButton, setShowScrollButton] = useState(false);

  const navigate = useNavigate();
  const location = useLocation();

  const filter = !isLoading
    ? [
        {
          slug: "status",
          name: "Status",
          children: [
            {
              name: "All",
              id: "all-status",
            },
            { name: "Completed", id: "completed" },
            { name: "Conceptual", id: "conceptual" },
          ],
        },
        {
          slug: "type",
          name: "Type",
          children: [
            {
              name: "All",
              id: "all-type",
            },
            ...(projectTypes || []),
          ],
        },
      ]
    : [];

  const setParams = (key: string, value: string) => {
    const queryParams = new URLSearchParams(location.search);

    if (value.startsWith("all-")) {
      queryParams.delete(key);
    } else {
      queryParams.set(key, value);
    }

    navigate(`${location.pathname}?${queryParams.toString()}`);
  };

  const getParams = useCallback(() => {
    const queryParams = new URLSearchParams(location.search);
    const status = queryParams.get("status");
    const type = Number(queryParams.get("type"));

    return {
      status,
      type,
    };
  }, [location]);

  const { status, type } = getParams();

  const filterProjects = useCallback(() => {
    const filtered = allProjects?.filter((project) => {
      const filterByStatus = !!status ? project.meta.status === status : true;

      const filterByType = !!type
        ? project.project_types?.includes(type)
        : true;

      return filterByStatus && filterByType;
    });

    setFilteredProjects(filtered);
  }, [getParams, allProjects]);

  useEffect(() => {
    filterProjects();
  }, [filterProjects]);

  const handleScroll = () => {
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    setShowScrollButton(scrollTop > 2000);
  };

  useEffect(() => {
    window.addEventListener("scroll", handleScroll);

    // Initial check in case page is already scrolled
    handleScroll();

    return () => {
      window.removeEventListener("scroll", handleScroll);
    };
  }, [handleScroll]);

  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  return (
    <BusyWrapper isBusy={isLoading}>
      <div className="projects container">
        <h3 className="title">Projects</h3>
        <div className="filter flex justify-between">
          {filter?.map((group) => {
            const isTypeGroup = group.slug === "type";

            const selectedId = isTypeGroup
              ? type || `all-${group.slug}`
              : status || `all-${group.slug}`;

            return (
              <div key={group.slug} className="filter-group flex column">
                <p className="filter-group-title text-sm bold flex align-center">
                  {group.name}
                  <i
                    onClick={() => setFilterOpen(!isFilterOpen)}
                    className={`fa-solid ${
                      isFilterOpen ? "fa-minus" : "fa-plus"
                    }`}
                  ></i>
                </p>
                <ul
                  className={` ${
                    isFilterOpen
                      ? "open flex columns filter-group-items"
                      : "flex columns filter-group-items"
                  }`}
                >
                  {group.children?.map((child) => {
                    const isActive = child.id === selectedId;

                    return (
                      <li
                        key={child.id}
                        onClick={() => {
                          !isActive &&
                            setParams(group.slug, child.id.toString());
                        }}
                        className={`${
                          isActive
                            ? "bold text-sm filter-item"
                            : "text-sm filter-item"
                        }`}
                      >
                        {child.name}
                      </li>
                    );
                  })}
                </ul>
              </div>
            );
          })}
        </div>
        {filteredProjects?.length ? (
          <ul className="content projects-list">
            {filteredProjects?.map((project) => {
              const places = project.meta.places
                ? project.meta.places.split(",")
                : null;

              return (
                <li key={project.id} className="flex column projects-item">
                  <p className="sub-title">{project.plain_title}</p>
                  <div className="image-wrapper flex noGrow">
                    {project.featured_image_urls ? (
                      <img
                        src={
                          project.featured_image_urls.medium || placeholderImage
                        }
                        className="image"
                      />
                    ) : (
                      <p className="text-sm">{project.plain_content || ""}</p>
                    )}
                  </div>
                  <div className="flex justify-between light">
                    {places ? (
                      <div className="flex column noGrow">
                        {places?.map((place) => (
                          <div key={place.trim()} className="text-md">
                            {place}
                          </div>
                        ))}
                      </div>
                    ) : (
                      ""
                    )}
                    <div className="text-md year">{project.meta.year}</div>
                    {project.meta.hasDetailedPage ? (
                      <i
                        onClick={() =>
                          navigate(`${location.pathname}${project.slug}`)
                        }
                        className="fa-solid fa-arrow-right text-md"
                      />
                    ) : (
                      ""
                    )}
                  </div>
                </li>
              );
            })}
          </ul>
        ) : (
          <p className="text-sm">No projects found</p>
        )}
        {showScrollButton && (
          <i
            onClick={scrollToTop}
            className="fa-solid fa-arrow-right scroll-to-top"
          />
        )}
      </div>
    </BusyWrapper>
  );
};

export default ProjectsPage;
