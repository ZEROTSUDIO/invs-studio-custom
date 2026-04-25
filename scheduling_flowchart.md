# Production Schedule Generation Flowchart

This flowchart details the step-by-step logical process of how the scheduling algorithm in `M_schedule::generate()` works.

```mermaid
flowchart TD
    %% Styling
    classDef startEnd fill:#2d3748,stroke:#4a5568,color:#fff,stroke-width:2px;
    classDef process fill:#ebf8ff,stroke:#3182ce,color:#2b6cb0,stroke-width:2px;
    classDef decision fill:#faf5ff,stroke:#805ad5,color:#553c9a,stroke-width:2px;
    classDef db fill:#f0fff4,stroke:#38a169,color:#276749,stroke-width:2px;
    classDef tier fill:#fffaf0,stroke:#dd6b20,color:#c05621,stroke-width:2px;

    Start([Start Generate]):::startEnd --> FetchDB[(Fetch Schedulable Orders<br/>& In-Progress Jobs)]:::db
    FetchDB --> EmptyCheck{Are there<br/>orders to<br/>schedule?}:::decision
    
    EmptyCheck -- No --> End([End]):::startEnd
    EmptyCheck -- Yes --> Anchor[Calculate Anchor Time<br/>End of latest In-Progress OR Now<br/>Clamped to Business Hours]:::process

    Anchor --> LoopStart{Loop through<br/>each Order}:::decision

    %% Tier 1: Quick Insert
    LoopStart --> QICheck{Is Quick-Insert?<br/>Small AND Near Deadline<br/>AND Fits Today}:::decision
    QICheck -- Yes --> QITier[Add to Quick-Insert Tier]:::tier
    
    %% Tier 2 & 3: Slack Analysis
    QICheck -- No --> SlackCalc[Calculate Worst-Case Slack<br/>Assume job runs last]:::process
    SlackCalc --> SlackCheck{Is Slack <br/> Safety Buffer?}:::decision
    
    SlackCheck -- Yes --> UTier[Add to Urgent Tier]:::tier
    SlackCheck -- No --> NTier[Add to Normal Tier]:::tier

    %% Re-loop
    QITier --> LoopStart
    UTier --> LoopStart
    NTier --> LoopStart

    %% Sorting Phase
    LoopStart -- All Orders Classified --> SortPhase[Sorting Phase]:::process
    
    SortPhase --> SortQI[Sort Quick-Insert:<br/>SJF Shortest First]:::process
    SortPhase --> SortU[Sort Urgent:<br/>EDF Deadline First + SJF]:::process
    SortPhase --> SortN[Sort Normal:<br/>EDF Deadline First + SJF]:::process

    %% Database Operations
    SortQI --> ClearDB[(Clear Old Unstarted<br/>Schedules in DB)]:::db
    SortU --> ClearDB
    SortN --> ClearDB

    ClearDB --> InsertQI[(Insert Quick-Insert<br/>Starting from NOW)]:::db
    InsertQI --> InsertU[(Insert Urgent<br/>Starting from Anchor Time)]:::db
    InsertU --> InsertN[(Insert Normal<br/>Starting after Urgent)]:::db
    
    InsertN --> Commit[(Commit Transaction)]:::db
    Commit --> End
```

### Explanatory Notes
1. **Anchor Time**: This ensures the continuous flow of production. If a job is currently running, the schedule builds off its projected completion time.
2. **Quick-Insert**: This bypasses the normal queue entirely, slotting right into today's timeline if there's an operational gap.
3. **Worst-Case Slack Analysis**: The algorithm mathematically forces a task to the end of the line, calculates its projected end date, and checks if it misses the deadline. If it does (or gets dangerously close based on the safety buffer), it gets promoted to Urgent.
4. **EDF + SJF Sorting**: The recent fix ensures that if multiple jobs land on the exact same deadline, the algorithm resolves the tie by processing the shortest job first (SJF), thereby clearing the backlog faster.
