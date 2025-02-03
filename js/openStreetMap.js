const extractNodeIdFromUrl = (url) => {
    const match = url.match(/node=(\d+)/); 
    return match ? match[1] : null;
  };
  
  const fetchNodeData = async (nodeId) => {
    const overpassUrl = `https://overpass-api.de/api/interpreter`;
    const query = `[out:json];node(${nodeId});out;`;
  
    try {
      const response = await fetch(overpassUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `data=${encodeURIComponent(query)}`,
      });
  
      if (!response.ok) {
        throw new Error(`Erreur lors de la requête : ${response.statusText}`);
      }
  
      const data = await response.json();
      return data.elements.length > 0 ? data.elements[0] : null;
    } catch (error) {
      console.error("Erreur :", error);
      return null;
    }
  };
  
  const generateImageLink = (nodeData) => {
    if (nodeData.tags && nodeData.tags.image) {
      return nodeData.tags.image;
    }
  
    const panoramaxBase = "https://panoramax.openstreetmap.fr/images/";
    if (nodeData.tags && nodeData.tags["panoramax:id"]) {
      const id = nodeData.tags["panoramax:id"];
      return `${panoramaxBase}${id}.jpg`;
    }
  
    return null;
  };
  
  const displayNodeImage = (nodeId, imageUrl) => {
    const container = document.getElementById("image-container");
    const img = document.createElement("img");
    img.src = imageUrl;
    img.alt = `Image pour le nœud ${nodeId}`;
    img.style.margin = "10px";
    img.style.maxWidth = "300px";
    img.style.borderRadius = "8px";
    container.appendChild(img);
  };
  
  const processNodeLinks = async (links) => {
    for (const link of links) {
      const nodeId = extractNodeIdFromUrl(link);
      if (nodeId) {
        console.log(`Récupération des données pour le nœud : ${nodeId}`);
        const nodeData = await fetchNodeData(nodeId);
  
        if (nodeData) {
          const imageUrl = generateImageLink(nodeData);
          if (imageUrl) {
            console.log(`Image trouvée pour le nœud ${nodeId} : ${imageUrl}`);
            displayNodeImage(nodeId, imageUrl);
          } else {
            console.warn(`Aucune image trouvée pour le nœud ${nodeId}.`);
          }
        } else {
          console.warn(`Aucune donnée trouvée pour le nœud ${nodeId}.`);
        }
      } else {
        console.warn(`Impossible d'extraire un ID de nœud depuis le lien : ${link}`);
      }
    }
  };
  
  const nodeLinks = [];
  document.querySelectorAll("#resto_map").forEach((input) => {
    nodeLinks.push(input.value);
  });
  
  const imageContainer = document.createElement("div");
  imageContainer.id = "image-container";
  imageContainer.style.display = "flex";
  imageContainer.style.flexWrap = "wrap";
  document.body.appendChild(imageContainer);
  
  processNodeLinks(nodeLinks);
  