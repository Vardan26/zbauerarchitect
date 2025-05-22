import {
  createContext,
  ReactNode,
  useContext,
  useEffect,
  useState,
} from "react";
import { Project, ProjectsContextType, ProjectTypeTerm } from "./types";

const ProjectsContext = createContext<ProjectsContextType | null>(null);

export const useProjects = () => {
  const context = useContext(ProjectsContext);
  if (!context) {
    throw new Error("useProjects must be used within a ProjectsProvider");
  }
  return context;
};

type Props = {
  children: ReactNode;
};

export const ProjectsProvider = ({ children }: Props) => {
  const [allProjects, setAllProjects] = useState<Project[]>();
  const [groupedProjects, setGroupedProjects] = useState<
    Record<string, Project[]>
  >({});
  const [isLoading, setIsLoading] = useState(true);
  const [projectTypes, setProjectTypes] = useState<ProjectTypeTerm[]>();

  const fetchAllData = async () => {
    console.log("new date", new Date().toISOString());
    const projectsRes = await fetch(
      "/wp-json/wp/v2/projects?per_page=100&_embed"
    );

    const projectTypesRes = await fetch("/wp-json/wp/v2/project_types");

    const projects: Project[] = await projectsRes.json();
    const projectTypes: ProjectTypeTerm[] = await projectTypesRes.json();

    setAllProjects(projects);
    setProjectTypes(projectTypes);
    setIsLoading(false);
  };

  useEffect(() => {
    fetchAllData();
  }, []);

  useEffect(() => {
    if (!allProjects) return;

    const grouped: Record<string, Project[]> = {};

    allProjects.forEach((project) => {
      if (project.meta?.showInHomePage) {
        const category =
          project.project_category_data?.description || "Uncategorized";
        if (!grouped[category]) grouped[category] = [];
        grouped[category].push(project);
      }
    });

    setGroupedProjects(grouped);
  }, [allProjects]);

  const data: ProjectsContextType = {
    allProjects,
    groupedProjects,
    projectTypes,
    isLoading,
  };

  return (
    <ProjectsContext.Provider value={data}>{children}</ProjectsContext.Provider>
  );
};
