ALTER TABLE public.mis_expense_line
ADD COLUMN exdtline_proj_id bigint NULL;

ALTER TABLE public.mis_expense_line
ADD CONSTRAINT fk_expense_project
FOREIGN KEY (exdtline_proj_id)
REFERENCES mis_projects(project_id);