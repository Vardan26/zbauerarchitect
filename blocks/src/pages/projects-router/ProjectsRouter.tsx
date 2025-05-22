import { Routes, Route, BrowserRouter, MemoryRouter } from "react-router-dom";
import ProjectsPage from "../projects-page/ProjectsPage";
import SingleProject from "../single-project/SingleProject";

import { useProjects } from "../../DataContext";
import { BASE_PATH } from "../../constants";

const isEditor = window.location.pathname.includes("/wp-admin/site-editor.php");

const ProjectsRouter = () => {
  const Router = isEditor ? MemoryRouter : BrowserRouter;
  const routerProps = isEditor
    ? { initialEntries: ["/projects."] }
    : { basename: BASE_PATH };

  const { isLoading, allProjects, projectTypes } = useProjects();

  return (
    <Router {...routerProps}>
      <Routes>
        <Route
          path="/projects"
          element={
            <ProjectsPage
              isLoading={isLoading}
              allProjects={allProjects}
              projectTypes={projectTypes}
            />
          }
        />
        <Route
          path="/projects/:slug"
          element={
            <SingleProject
              isLoading={isLoading}
              allProjects={allProjects}
              projectTypes={projectTypes}
            />
          }
        />
      </Routes>
    </Router>
  );
};

export default ProjectsRouter;
