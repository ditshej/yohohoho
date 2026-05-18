---
name: grill-me
description: Interview the user relentlessly about a plan or design until reaching shared understanding. Use for the Align phase before starting OpenSpec implementation, or when stress-testing any proposal or design decision.
license: Based on mattpocock/skills (https://github.com/mattpocock/skills) and benithors/skills (https://github.com/benithors/skills) — MIT
compatibility: No external tools required.
---

Interview the user relentlessly about every aspect of the plan until we reach a shared understanding. Walk down each branch of the decision tree, resolving dependencies between decisions one by one.

**Input**: An optional topic name, plan, or design description after `/grill-me`. If empty, ask the user what plan to stress-test. Can also reference an existing `proposal.md` or brainstorm briefing.

**Steps**

1. **If no input, ask what to grill**

   Ask: "What plan or design do you want to think through together? Paste it, describe it, or point me to a file."

2. **Load prior context**

   If a topic name was provided and `openspec/product/<topic>/briefing.md` exists, read it silently and use it as the starting context. Do not ask the user to re-describe the problem — summarise what you read in one sentence and move on.

3. **Build the decision tree**

   Read any referenced documents or code. Identify the key decisions and open questions across:
   - Architecture and technical approach
   - Scope and boundaries (what's in/out)
   - Data model and flow
   - Error handling and edge cases
   - Dependencies and integrations

4. **Interview — one question at a time**

   For each question:
   - Ask it clearly and directly
   - **Provide your own recommended answer** (don't just ask — take a position)
   - Wait for the user's response before moving on
   - If the user's answer changes the decision tree, update your understanding

   **If a question can be answered by exploring the codebase**: explore it yourself and answer it rather than asking.

5. **Produce shared-understanding summary**

   Show the summary inline AND write it to `openspec/product/<topic>/aligned.md` using the Write tool. State the file path before writing: "Writing shared-understanding to `openspec/product/<topic>/aligned.md`…"

   ```markdown
   # Shared Understanding: <plan name>

   ## Agreed Decisions
   - **<decision>**: <agreed answer and rationale>
   - ...

   ## Open Questions Resolved
   - <question>: <answer>

   ## Remaining Open Questions
   - <anything still unresolved>
   ```

6. **Bridge to next step**

   After producing the summary, suggest:
   > "Shared understanding saved to `openspec/product/<topic>/aligned.md`. Run `/clear` to reset context, then use the aligned decisions to create `openspec/ROADMAP.md` and start each change with `/opsx:new <name>`."

**Guardrails**
- Ask ONE question at a time — never a list
- Always give your recommended answer before waiting for theirs
- Do NOT implement anything during this session
- Stop when the user feels aligned, not when every edge case is covered
- If discussion is going in circles, summarize the current position and ask if it's acceptable to move on
