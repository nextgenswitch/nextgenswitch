from pathlib import Path
path = Path("index.html")
text = path.read_text()
text = text.replace("      constructor(wsUrl,wsStatusId) {", "      constructor(wsUrl) {")
text = text.replace("        this.wsStatus = $(\"#\" + wsStatusId);", "        this.wsStatus = $('#ws-status');")
path.write_text(text)