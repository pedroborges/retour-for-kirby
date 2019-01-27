

export default function (doc, id) {
  let ctx = doc.getElementById(id).getContext('2d');

  let gradient1 = ctx.createLinearGradient(0, 0, 0, 225);
  gradient1.addColorStop(0, 'rgba(66, 113, 174, 1)');
  gradient1.addColorStop(0.7, 'rgba(66, 113, 174, 0.5)');
  gradient1.addColorStop(1, 'rgba(255, 255, 255, 0.25)');

  let gradient2 = ctx.createLinearGradient(0, 0, 0, 450);
  gradient2.addColorStop(0, 'rgba(175, 175, 175, 0.9)');
  gradient2.addColorStop(0.25, 'rgba(204, 204, 204, 0.5)');
  gradient2.addColorStop(1, 'rgba(255, 255, 255, 0)');

  return {
    redirects: gradient1,
    fails: gradient2
  }
}
