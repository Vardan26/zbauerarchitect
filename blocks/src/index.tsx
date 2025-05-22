import { createPortal } from "react-dom";

import { registerBlockType } from "@wordpress/blocks";
import { useBlockProps } from "@wordpress/block-editor";
import { createRoot } from "@wordpress/element";

import FeaturedProjects from "./components/featured-projects/FeaturedProjects";
import AboutInfo from "./components/about-info/AboutInfo";

import ProjectsRouter from "./pages/projects-router/ProjectsRouter";

import { ProjectsProvider } from "./DataContext";

registerBlockType("zbauerarchitect/featured-projects", {
  title: "Featured Projects",
  category: "widgets",
  icon: "portfolio",
  attributes: {},
  edit: () => {
    const blockProps = useBlockProps();
    return (
      <div {...blockProps}>
        <ProjectsProvider>
          <FeaturedProjects />
        </ProjectsProvider>
      </div>
    );
  },
  save: () => null,
});

registerBlockType("zbauerarchitect/about-info", {
  title: "About Info",
  category: "widgets",
  icon: "info",
  attributes: {},
  edit: () => {
    const blockProps = useBlockProps();
    return (
      <div {...blockProps}>
        <ProjectsProvider>
          <AboutInfo />
        </ProjectsProvider>
      </div>
    );
  },
  save: () => null,
});

registerBlockType("zbauerarchitect/projects-router", {
  title: "Projects Router",
  category: "theme",
  icon: "admin-site",
  attributes: {},
  edit: () => {
    const blockProps = useBlockProps();
    return (
      <div {...blockProps}>
        <ProjectsProvider>
          <ProjectsRouter />
        </ProjectsProvider>
      </div>
    );
  },
  save: () => null,
});

// 🔁 Hydrate on Frontend (if needed)

const AppWithPortals = () => {
  const featuredTarget = document.getElementById("featured-projects-app");
  const aboutTarget = document.getElementById("about-info-app");

  return (
    <ProjectsProvider>
      {featuredTarget && createPortal(<FeaturedProjects />, featuredTarget)}
      {aboutTarget && createPortal(<AboutInfo />, aboutTarget)}
    </ProjectsProvider>
  );
};

const AppProjects = () => {
  return (
    <ProjectsProvider>
      <ProjectsRouter />
    </ProjectsProvider>
  );
};

document.addEventListener("DOMContentLoaded", () => {
  const rootEl = document.getElementById("react-root");
  const projectsRootEl = document.getElementById("projects-router-app");

  if (rootEl) {
    const root = createRoot(rootEl);
    root.render(<AppWithPortals />);
  }
  if (projectsRootEl) {
    const projectsRoot = createRoot(projectsRootEl);
    projectsRoot.render(<AppProjects />);
  }
});
