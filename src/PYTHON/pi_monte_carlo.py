from mpi4py import MPI
from random import random
from math import pi
import time
import sys
import json

# Attach to the cluster and find out who I am and how big it is
comm = MPI.COMM_WORLD
my_rank = comm.Get_rank()
cluster_size = comm.Get_size()

# Number to start on, based on the node’s rank
start_number = (my_rank * 2)

# When to stop. Play around with this value!
throws = int(sys.argv[1])

#fichier pour stocker le résultat du calcul
fileResultCalcul = sys.argv[2]

# Make a note of the start time
start = time.time()

count = 0
# Loop through the numbers using rank number to divide the work
for candidate_number in range(start_number, throws, cluster_size * 2):
    print(candidate_number)
    x = random()
    y = random()

    if ((x * x + y * y)< 1):
        count +=1

# Once complete, send results to the governing node
results = comm.gather(count, root=0)

# If I am the governing node, show the results
if my_rank == 0:
    # How long did it take?
    total = sum(results)
    pi_calc = 4.0 * total / (throws*cluster_size)
    end = round(time.time() - start, 2)
    error = abs((pi_calc - pi))/pi

    #print("Throws on each: " + str(throws))
    #print("Total throws : " + str(cluster_size * throws))
    #print("Time elasped: " + str(end) + " seconds")
    # Each process returned an array, so lets merge them
    #print("Result: ", results)
    #print("Total : ",total)
    #print("Val of Pi : ",pi_calc)
    #print("Error : ",error)

    dictResults = {"executionTime":end, "pi":pi_calc, "error" : error}
    with open(fileResultCalcul, "w") as file:
        file.write(json.dumps(dictResults, indent=3))