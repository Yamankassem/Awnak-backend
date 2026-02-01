openapi: 3.0.0
info:
  title: Applications API
  description: |
    API for managing volunteer applications, tasks, feedback, and task hours.
    This API follows RESTful principles and includes comprehensive filtering,
    caching, and role-based access control.
    
    Built with Laravel, this API leverages the framework's powerful features:
    - Elegant syntax and expressive routing
    - Robust dependency injection container
    - Comprehensive database ORM with Eloquent
    - Built-in caching and session management
    - Queue system for background processing
    - Real-time event broadcasting capabilities
  version: 1.0.0
  contact:
    name: API Support
    email: support@example.com
  license:
    name: MIT
    url: https://opensource.org/licenses/MIT

servers:
  - url: http://localhost:8000/api
    description: Local development server
  - url: https://api.example.com
    description: Production server

# ============================================================================
# PATH DEFINITIONS
# ============================================================================

paths:
  # --------------------------------------------------------------------------
  # APPLICATIONS ENDPOINTS
  # --------------------------------------------------------------------------
  
  /applications:
    get:
      summary: List applications with filtering
      description: |
        Retrieve paginated list of volunteer applications.
        Access control is enforced based on user roles and permissions.
      security:
        - bearerAuth: []
      parameters:
        - name: per_page
          in: query
          description: Items per page (1-100)
          schema:
            type: integer
            minimum: 1
            maximum: 100
            default: 15
        - name: page
          in: query
          description: Page number
          schema:
            type: integer
            minimum: 1
            default: 1
        - name: status
          in: query
          description: Filter by application status
          schema:
            type: string
            enum: [pending, waiting_list, approved, rejected]
        - name: opportunity_id
          in: query
          description: Filter by opportunity ID
          schema:
            type: integer
        - name: volunteer_id
          in: query
          description: Filter by volunteer ID
          schema:
            type: integer
        - name: coordinator_id
          in: query
          description: Filter by coordinator ID
          schema:
            type: integer
        - name: from_date
          in: query
          description: Filter from date (YYYY-MM-DD)
          schema:
            type: string
            format: date
        - name: to_date
          in: query
          description: Filter to date (YYYY-MM-DD)
          schema:
            type: string
            format: date
        - name: search
          in: query
          description: Search in descriptions
          schema:
            type: string
            minLength: 2
      responses:
        '200':
          description: Applications retrieved successfully
        '401':
          description: Unauthorized - Authentication required
        '403':
          description: Forbidden - Insufficient permissions
        '422':
          description: Validation error
    
    post:
      summary: Create new application
      description: Create a volunteer application for an opportunity
      security:
        - bearerAuth: []
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required: [opportunity_id, volunteer_id, coordinator_id, description]
              properties:
                opportunity_id:
                  type: integer
                  description: Related opportunity ID
                  example: 5
                volunteer_id:
                  type: integer
                  description: Volunteer ID
                  example: 10
                coordinator_id:
                  type: integer
                  description: Coordinator ID
                  example: 3
                description:
                  type: string
                  description: Application description
                  example: "Experienced volunteer with strong communication skills"
                status:
                  type: string
                  enum: [pending, waiting_list, approved, rejected]
                  default: pending
                  description: Initial application status
      responses:
        '201':
          description: Application created successfully
        '400':
          description: Bad request
        '401':
          description: Unauthorized
        '403':
          description: Forbidden
        '422':
          description: Validation failed

  /applications/{id}:
    get:
      summary: Get specific application
      description: Retrieve detailed application information
      security:
        - bearerAuth: []
      parameters:
        - name: id
          in: path
          required: true
          description: Application ID
          schema:
            type: integer
      responses:
        '200':
          description: Application retrieved
        '401':
          description: Unauthorized
        '403':
          description: Forbidden
        '404':
          description: Application not found
    
    put:
      summary: Update application
      description: Update application details
      security:
        - bearerAuth: []
      parameters:
        - name: id
          in: path
          required: true
          description: Application ID
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                description:
                  type: string
                  description: Updated description
                coordinator_id:
                  type: integer
                  description: New coordinator ID
                status:
                  type: string
                  enum: [pending, waiting_list, approved, rejected]
                  description: Updated status
      responses:
        '200':
          description: Application updated
        '400':
          description: Bad request
        '401':
          description: Unauthorized
        '403':
          description: Forbidden
        '404':
          description: Not found
        '422':
          description: Validation failed
    
    delete:
      summary: Delete application
      description: |
        Delete an application and associated tasks.
        This action triggers audit logging and cache invalidation.
      security:
        - bearerAuth: []
      parameters:
        - name: id
          in: path
          required: true
          description: Application ID
          schema:
            type: integer
      responses:
        '200':
          description: Application deleted
        '401':
          description: Unauthorized
        '403':
          description: Forbidden
        '404':
          description: Not found

  /applications/{id}/status:
    patch:
      summary: Update application status
      description: |
        Update application status with optional reason.
        Triggers notifications and waiting list management.
      security:
        - bearerAuth: []
      parameters:
        - name: id
          in: path
          required: true
          description: Application ID
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required: [status]
              properties:
                status:
                  type: string
                  enum: [pending, waiting_list, approved, rejected]
                  description: New status
                reason:
                  type: string
                  description: Status change reason
      responses:
        '200':
          description: Status updated
        '400':
          description: Bad request
        '401':
          description: Unauthorized
        '403':
          description: Forbidden
        '404':
          description: Not found
        '422':
          description: Validation failed

  # --------------------------------------------------------------------------
  # TASKS ENDPOINTS
  # --------------------------------------------------------------------------
  
  /tasks:
    get:
      summary: List tasks with filtering
      description: Retrieve paginated list of tasks
      security:
        - bearerAuth: []
      parameters:
        - name: per_page
          in: query
          description: Items per page
          schema:
            type: integer
            default: 15
        - name: status
          in: query
          description: Filter by task status
          schema:
            type: string
            enum: [preparation, active, complete, cancelled]
        - name: application_id
          in: query
          description: Filter by application ID
          schema:
            type: integer
        - name: search
          in: query
          description: Search in title/description
          schema:
            type: string
      responses:
        '200':
          description: Tasks retrieved
        '401':
          description: Unauthorized
        '403':
          description: Forbidden
    
    post:
      summary: Create new task
      description: Create a task for an application
      security:
        - bearerAuth: []
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required: [application_id, title, description, due_date]
              properties:
                application_id:
                  type: integer
                  description: Parent application ID
                title:
                  type: string
                  description: Task title
                description:
                  type: string
                  description: Task description
                status:
                  type: string
                  enum: [preparation, active, complete, cancelled]
                  default: active
                  description: Initial status
                due_date:
                  type: string
                  format: date
                  description: Task due date
      responses:
        '201':
          description: Task created
        '400':
          description: Bad request
        '401':
          description: Unauthorized
        '403':
          description: Forbidden
        '422':
          description: Validation failed

  /tasks/{id}/log-hours:
    post:
      summary: Log task hours
      description: |
        Log working hours for a task with date overlap validation.
        Updates volunteer total hours automatically.
      security:
        - bearerAuth: []
      parameters:
        - name: id
          in: path
          required: true
          description: Task ID
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required: [hours, started_date, ended_date]
              properties:
                hours:
                  type: integer
                  minimum: 1
                  maximum: 12
                  description: Hours worked
                started_date:
                  type: string
                  format: date
                  description: Start date
                ended_date:
                  type: string
                  format: date
                  description: End date
                note:
                  type: string
                  description: Work description
      responses:
        '201':
          description: Hours logged
        '400':
          description: Bad request
        '401':
          description: Unauthorized
        '403':
          description: Forbidden
        '404':
          description: Not found
        '422':
          description: Overlap validation failed

  # --------------------------------------------------------------------------
  # FEEDBACKS ENDPOINTS
  # --------------------------------------------------------------------------
  
  /feedbacks:
    post:
      summary: Create feedback
      description: |
        Create performance evaluation or task review.
        Only completed tasks can be evaluated.
        Updates volunteer average rating automatically.
      security:
        - bearerAuth: []
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required: [task_id, name_of_org, name_of_vol, rating, comment]
              properties:
                task_id:
                  type: integer
                  description: Task ID
                name_of_org:
                  type: string
                  description: Organization name
                name_of_vol:
                  type: string
                  description: Volunteer name
                rating:
                  type: integer
                  minimum: 1
                  maximum: 5
                  description: Performance rating (1-5)
                comment:
                  type: string
                  description: Evaluation comments
                metrics:
                  type: array
                  description: Performance metrics
                  items:
                    type: object
                    properties:
                      name:
                        type: string
                        description: Metric name
                      score:
                        type: integer
                        minimum: 1
                        maximum: 5
                        description: Metric score
                      notes:
                        type: string
                        description: Metric notes
      responses:
        '201':
          description: Feedback created
        '400':
          description: Bad request
        '401':
          description: Unauthorized
        '403':
          description: Forbidden
        '422':
          description: Task not completed or validation failed

# ============================================================================
# COMPONENTS
# ============================================================================

components:
  securitySchemes:
    bearerAuth:
      type: http
      scheme: bearer
      bearerFormat: JWT
      description: |
        JWT Bearer Token authentication.
        Include in header: `Authorization: Bearer {token}`
  
  schemas:
    Application:
      type: object
      properties:
        id:
          type: integer
          description: Application ID
        opportunity_id:
          type: integer
          description: Opportunity ID
        volunteer_id:
          type: integer
          description: Volunteer ID
        coordinator_id:
          type: integer
          description: Coordinator ID
        description:
          type: string
          description: Application description
        status:
          type: string
          enum: [pending, waiting_list, approved, rejected]
          description: Current status
        created_at:
          type: string
          format: date-time
          description: Creation timestamp
        updated_at:
          type: string
          format: date-time
          description: Last update timestamp
    
    Task:
      type: object
      properties:
        id:
          type: integer
          description: Task ID
        application_id:
          type: integer
          description: Parent application ID
        title:
          type: string
          description: Task title
        description:
          type: string
          description: Task description
        status:
          type: string
          enum: [preparation, active, complete, cancelled]
          description: Task status
        due_date:
          type: string
          format: date
          description: Due date
        completed_at:
          type: string
          format: date-time
          description: Completion timestamp
        created_at:
          type: string
          format: date-time
          description: Creation timestamp
        updated_at:
          type: string
          format: date-time
          description: Last update timestamp
    
    Feedback:
      type: object
      properties:
        id:
          type: integer
          description: Feedback ID
        task_id:
          type: integer
          description: Task ID
        name_of_org:
          type: string
          description: Organization name
        name_of_vol:
          type: string
          description: Volunteer name
        rating:
          type: integer
          minimum: 1
          maximum: 5
          description: Performance rating
        comment:
          type: string
          description: Evaluation comments
        created_at:
          type: string
          format: date-time
          description: Creation timestamp
        updated_at:
          type: string
          format: date-time
          description: Last update timestamp
    
    TaskHour:
      type: object
      properties:
        id:
          type: integer
          description: Task hour ID
        task_id:
          type: integer
          description: Task ID
        hours:
          type: integer
          description: Hours worked
        started_date:
          type: string
          format: date
          description: Start date
        ended_date:
          type: string
          format: date
          description: End date
        note:
          type: string
          description: Work description
        created_at:
          type: string
          format: date-time
          description: Creation timestamp
        updated_at:
          type: string
          format: date-time
          description: Last update timestamp
  
  responses:
    Unauthorized:
      description: Authentication credentials missing or invalid
      content:
        application/json:
          schema:
            type: object
            properties:
              message:
                type: string
                example: "Unauthorized"
    
    Forbidden:
      description: Authenticated but insufficient permissions
      content:
        application/json:
          schema:
            type: object
            properties:
              message:
                type: string
                example: "Forbidden"
    
    NotFound:
      description: Resource not found
      content:
        application/json:
          schema:
            type: object
            properties:
              message:
                type: string
                example: "Resource not found"
    
    ValidationError:
      description: Request validation failed
      content:
        application/json:
          schema:
            type: object
            properties:
              message:
                type: string
                example: "The given data was invalid."
              errors:
                type: object
                additionalProperties:
                  type: array
                  items:
                    type: string

# ============================================================================
# SECURITY
# ============================================================================

security:
  - bearerAuth: []

# ============================================================================
# TAGS (Optional organization)
# ============================================================================

tags:
  - name: Applications
    description: Volunteer application management
  - name: Tasks
    description: Task management and tracking
  - name: Feedbacks
    description: Performance evaluations and reviews
  - name: Task Hours
    description: Working hour logging and reporting