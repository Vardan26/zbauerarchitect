export type Project = {
  id: number;
  date: string;
  date_gmt: string;
  guid: { rendered: string };
  modified: string;
  modified_gmt: string;
  slug: string;
  status: string; // WordPress post status, e.g., "publish"
  type: "projects";
  link: string;

  title: { rendered: string };
  content: { rendered: string; protected: boolean };
  excerpt: { rendered: string; protected: boolean };

  featured_media: number;
  featured_image_urls?: {
    thumb: string | null;
    medium: string | null;
    full: string | null;
  };

  plain_title?: string;
  plain_content?: string;

  project_category?: number[]; // term IDs
  project_types?: number[]; // term IDs

  project_category_data?: {
    name: string;
    slug: string;
    description: string;
  } | null;

  // Meta fields from get_project_fields()
  meta: {
    year?: string;
    places?: string;
    client?: string;
    project_number?: string;
    principal_architect?: string;
    architects?: string;
    heading?: string;
    wireframe?: string;
    status?: "completed" | "conceptual"; // Only these two allowed
    gallery?: string[]; // array of image URLs
    showInHomePage?: boolean;
    hasDetailedPage?: boolean;
  };

  // Additional fields
  _links?: any;
};

export type ProjectTypeTerm = {
  id: number;
  count: number;
  description: string;
  link: string;
  name: string;
  slug: string;
  taxonomy: "project_types";
  parent: number;
  meta: Record<string, any>;
};

export type ProjectsContextType = {
  allProjects?: Project[];
  groupedProjects: Record<string, Project[]>;
  projectTypes?: ProjectTypeTerm[];
  isLoading: boolean;
};
