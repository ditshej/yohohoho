---
name: brainstorm
description: Product-level ideation before starting any OpenSpec change. Use when the user wants to explore what to build, think through a product idea, or is not yet sure what OpenSpec change to create.
license: Adapted from benithors/skills (https://github.com/benithors/skills) — MIT
compatibility: No external tools required.
---

Facilitate open-ended product ideation. This runs **before** any OpenSpec change is created — at the product level, not the change level.

**Input**: An optional topic, problem statement, or product idea after `/brainstorm`. If empty, ask the user what they want to explore.

**Mode: Divergent first, then convergent**

Start with expansive, open-ended questions — do not evaluate ideas yet. Only after enough surface area is covered, move toward focusing and summarizing.

**Steps**

1. **If no input, ask what to brainstorm**

   Ask: "What do you want to explore? Describe the problem, opportunity, or idea — even vaguely."

2. **Divergent phase: explore the space**

   Ask open-ended questions to understand the problem from multiple angles. Use one question at a time. Cover:
   - The problem being solved (who, what, why)
   - The context and constraints (tech stack, existing system, time)
   - What success looks like
   - What has been tried or considered
   - Adjacent ideas or inspirations

   **Do not evaluate or filter yet.** Just explore.

3. **Convergent phase: focus**

   When the space feels adequately explored, shift toward synthesis:
   - What is the core thing to build?
   - What are the rough "capabilities" (OpenSpec terms)?
   - What is explicitly NOT in scope?

4. **Produce the product briefing**

   Write a markdown summary and show it inline:

   ```markdown
   # Product Briefing: <topic>

   ## Problem
   <what problem are we solving, for whom>

   ## Vision
   <what does success look like>

   ## Constraints
   <tech stack, timeline, resources, non-goals>

   ## Rough Capabilities
   - `<kebab-case-name>`: <one-line description>
   - ...

   ## Open Questions
   - <anything still unclear>
   ```

5. **Bridge to next step**

   After producing the briefing, suggest:
   > "Use this briefing to create a `openspec/ROADMAP.md` with the capabilities as changes, then start each change with `/opsx:new <name>`. Or run `/grill-me` to stress-test the plan before proceeding."

**Guardrails**
- Do NOT create any OpenSpec changes or artifacts during brainstorm
- Do NOT write code
- Keep the session focused — if it's drifting into implementation details, redirect to the product level
- The output is the briefing document, not a plan for execution
