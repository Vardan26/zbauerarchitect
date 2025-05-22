import { useEffect, useState } from "@wordpress/element";
import { useProjects } from "../../DataContext";
import BusyWrapper from "../busy-wrapper";

import placeholderImage from "../../../../assets/images/placeholder.jpg";

const FeaturedProjects = () => {
  const { isLoading, groupedProjects } = useProjects();

  const projectsGroups = Object.entries(groupedProjects).sort();
  const [openedProjectIds, setOpenedProjectIds] = useState<number[]>([]);

  const checkIsOpen = (id: number) => openedProjectIds.includes(id);

  const toggleGroup = (id: number) => {
    if (checkIsOpen(id)) {
      setOpenedProjectIds(
        openedProjectIds.filter((openedId) => openedId !== id)
      );
    } else {
      setOpenedProjectIds([...openedProjectIds, id]);
    }
  };

  return (
    <BusyWrapper isBusy={isLoading}>
      <div className="featured-projects container">
        <h3 className="title">Featured Projects</h3>
        <div className="projects-wrapper">
          {projectsGroups.map(([category, items]) => {
            return (
              <div key={category} className="flex projects-group">
                <h3 className="sub-title">{category}</h3>
                <ul className="flex column items-list">
                  {items.map((project) => {
                    const isOpen = checkIsOpen(project.id);

                    return (
                      <li key={project.id} className="flex projects-item">
                        <div
                          className={`image-wrapper ${!isOpen ? "closed" : ""}`}
                        >
                          {
                            <img
                              src={
                                project.featured_image_urls?.medium ||
                                placeholderImage
                              }
                              className="image"
                            />
                          }
                        </div>
                        <div className="flex column description">
                          <div className="flex justify-between noGrow">
                            <p className="text-md bold">
                              {project.plain_title}
                            </p>
                            <i
                              onClick={() => toggleGroup(project.id)}
                              className={`fa-solid ${
                                isOpen ? "fa-minus" : "fa-plus"
                              }`}
                            ></i>
                          </div>

                          {isOpen ? (
                            <p className="text-md light">
                              {project.plain_content}
                            </p>
                          ) : (
                            ""
                          )}
                          {isOpen && project.meta.hasDetailedPage ? (
                            <div className="cta flex noGrow">
                              <a href={project.link} className="read-more link">
                                read more
                              </a>
                            </div>
                          ) : (
                            ""
                          )}
                        </div>
                      </li>
                    );
                  })}
                </ul>
              </div>
            );
          })}
        </div>
        <a href="/projects" className="link with-icon justify-end cta">
          complete portfolio
        </a>
      </div>
    </BusyWrapper>
  );
};

export default FeaturedProjects;
